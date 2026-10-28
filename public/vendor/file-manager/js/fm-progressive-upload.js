/**
 * Progressive Chunked Upload for Laravel File Manager
 * Caricamento progressivo a pacchetti (512 KB per pacchetto):
 * - Evita blocchi al 90%, limiti di body proxy/Nginx e timeout lato server
 * - Mantiene le immagini alla risoluzione e qualità originale 100% (nessun ridimensionamento agressivo)
 * - Mostra progresso pacchetto per pacchetto in tempo reale
 */
(function() {
    function initProgressiveChunkUpload() {
        if (!window.fmAxios) {
            setTimeout(initProgressiveChunkUpload, 50);
            return;
        }

        if (window.fmAxios._chunkUploadHooked) {
            return;
        }
        window.fmAxios._chunkUploadHooked = true;

        console.log('[File Manager] Progressive Chunked Upload attivo (512 KB per pacchetto, qualità originale).');

        const originalPost = window.fmAxios.post;

        window.fmAxios.post = async function(url, data, config) {
            if (url === 'upload' && data instanceof FormData) {
                return await handleProgressiveChunkUpload(data, config, originalPost);
            }
            return originalPost.apply(this, arguments);
        };
    }

    async function handleProgressiveChunkUpload(formData, config, originalPost) {
        const disk = formData.get('disk') || 'public';
        const path = formData.get('path') || '';
        const overwrite = formData.get('overwrite') || '0';
        const files = formData.getAll('files[]');

        if (!files || files.length === 0) {
            return originalPost.call(window.fmAxios, 'upload', formData, config);
        }

        // Elementi grafici per feedback visivo nel modale
        const modal = document.querySelector('.fm-modal-upload');
        let statusBadge = modal ? modal.querySelector('#fm-chunk-status') : null;
        if (modal && !statusBadge) {
            statusBadge = document.createElement('div');
            statusBadge.id = 'fm-chunk-status';
            statusBadge.className = 'alert alert-info py-2 px-3 my-2 text-xs font-semibold';
            statusBadge.style.fontSize = '12px';
            statusBadge.style.borderRadius = '6px';
            statusBadge.style.lineHeight = '1.4';
            const body = modal.querySelector('.modal-body') || modal;
            body.insertBefore(statusBadge, body.firstChild);
        }

        // Disabilita pulsante conferma durante upload per evitare doppie richieste
        const submitBtn = modal ? modal.querySelector('.modal-footer button.btn-info, .modal-footer button.btn-primary') : null;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
        }

        // Calcolo byte totali di tutti i file selezionati
        let totalAllBytes = 0;
        files.forEach(f => { totalAllBytes += f.size; });
        if (totalAllBytes === 0) totalAllBytes = 1;

        let cumulativeUploadedBytes = 0;
        const CHUNK_SIZE = 512 * 1024; // 512 KB per pacchetto (nessun blocco o timeout proxy)

        for (let fIdx = 0; fIdx < files.length; fIdx++) {
            const file = files[fIdx];
            const fileSizeMb = (file.size / 1024 / 1024).toFixed(2);
            const totalChunks = Math.max(1, Math.ceil(file.size / CHUNK_SIZE));
            const fileId = 'up_' + Date.now() + '_' + Math.random().toString(36).substring(2, 9);

            for (let chunkIdx = 0; chunkIdx < totalChunks; chunkIdx++) {
                const start = chunkIdx * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunkBlob = file.slice(start, end);

                const chunkFormData = new FormData();
                chunkFormData.append('chunk', chunkBlob, file.name);
                chunkFormData.append('chunk_index', chunkIdx);
                chunkFormData.append('total_chunks', totalChunks);
                chunkFormData.append('file_id', fileId);
                chunkFormData.append('filename', file.name);
                chunkFormData.append('disk', disk);
                chunkFormData.append('path', path);
                chunkFormData.append('overwrite', overwrite);

                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta) {
                    chunkFormData.append('_token', csrfMeta.getAttribute('content'));
                }

                const currentPercent = Math.min(99, Math.round((cumulativeUploadedBytes / totalAllBytes) * 100));

                if (statusBadge) {
                    statusBadge.className = 'alert alert-info py-2 px-3 my-2 text-xs font-semibold';
                    statusBadge.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>📦 <strong>Pacchetto ${chunkIdx + 1} di ${totalChunks}</strong> &bull; ${file.name}</span>
                            <span class="badge bg-primary text-white" style="font-size:11px;">${currentPercent}%</span>
                        </div>
                        <div class="text-muted" style="font-size:11px;">
                            File ${fIdx + 1} di ${files.length} (${fileSizeMb} MB) &bull; Caricamento progressivo pacchetti da 512 KB
                        </div>
                    `;
                }

                // Config con progresso del singolo chunk sommato al totale
                const chunkConfig = {
                    onUploadProgress: (progressEvt) => {
                        if (config && typeof config.onUploadProgress === 'function') {
                            const loadedSoFar = cumulativeUploadedBytes + progressEvt.loaded;
                            config.onUploadProgress({
                                loaded: Math.min(loadedSoFar, totalAllBytes - 1),
                                total: totalAllBytes
                            });
                        }
                    }
                };

                let attempts = 0;
                let success = false;
                let lastErr = null;

                while (attempts < 3 && !success) {
                    try {
                        attempts++;
                        await originalPost.call(window.fmAxios, 'chunk-upload', chunkFormData, chunkConfig);
                        success = true;
                    } catch (err) {
                        lastErr = err;
                        console.warn(`[File Manager] Errore pacchetto ${chunkIdx + 1}/${totalChunks} (tentativo ${attempts}):`, err);
                        if (attempts < 3) {
                            if (statusBadge) {
                                statusBadge.className = 'alert alert-warning py-2 px-3 my-2 text-xs font-semibold';
                                statusBadge.innerHTML = `⚠️ Connessione rallentata: rieseguo pacchetto ${chunkIdx + 1}/${totalChunks} (tentativo ${attempts + 1}/3)...`;
                            }
                            await new Promise(r => setTimeout(r, 1200));
                        }
                    }
                }

                if (!success) {
                    if (statusBadge) {
                        statusBadge.className = 'alert alert-danger py-2 px-3 my-2 text-xs font-semibold';
                        statusBadge.innerHTML = `❌ Errore caricamento al pacchetto ${chunkIdx + 1}/${totalChunks}.`;
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                    }
                    throw lastErr || new Error('Upload pacchetto fallito');
                }

                cumulativeUploadedBytes += chunkBlob.size;
                if (config && typeof config.onUploadProgress === 'function') {
                    const isLastPacket = (fIdx === files.length - 1 && chunkIdx === totalChunks - 1);
                    config.onUploadProgress({
                        loaded: isLastPacket ? totalAllBytes : cumulativeUploadedBytes,
                        total: totalAllBytes
                    });
                }
            }
        }

        if (statusBadge) {
            statusBadge.className = 'alert alert-success py-2 px-3 my-2 text-xs font-semibold';
            statusBadge.innerHTML = `✅ <strong>Completato!</strong> ${files.length} file caricati con successo con risoluzione e qualità originale.`;
        }

        if (config && typeof config.onUploadProgress === 'function') {
            config.onUploadProgress({ loaded: totalAllBytes, total: totalAllBytes });
        }

        return {
            data: {
                result: {
                    status: 'success',
                    message: 'uploaded'
                }
            },
            status: 200,
            statusText: 'OK'
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProgressiveChunkUpload);
    } else {
        initProgressiveChunkUpload();
    }
})();
