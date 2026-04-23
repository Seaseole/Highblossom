class VideoUploader {
    constructor(options = {}) {
        this.fileInput = options.fileInput;
        this.previewContainer = options.previewContainer;
        this.progressContainer = options.progressContainer;
        this.hiddenInput = options.hiddenInput;
        this.uploadUrl = options.uploadUrl;
        this.csrfToken = options.csrfToken;
        this.maxSize = options.maxSize || 30 * 1024 * 1024; // 30MB default
        this.acceptedTypes = options.acceptedTypes || ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo'];
        this.onUploadComplete = options.onUploadComplete || (() => {});
        this.onUploadError = options.onUploadError || (() => {});
        this.onPreview = options.onPreview || (() => {});

        this.init();
    }

    init() {
        if (!this.fileInput) return;

        this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
        
        // Drag and drop support
        if (this.previewContainer) {
            this.previewContainer.addEventListener('dragover', (e) => {
                e.preventDefault();
                this.previewContainer.classList.add('border-[#DC2626]', 'bg-[#DC2626]/5');
            });
            
            this.previewContainer.addEventListener('dragleave', (e) => {
                e.preventDefault();
                this.previewContainer.classList.remove('border-[#DC2626]', 'bg-[#DC2626]/5');
            });
            
            this.previewContainer.addEventListener('drop', (e) => {
                e.preventDefault();
                this.previewContainer.classList.remove('border-[#DC2626]', 'bg-[#DC2626]/5');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.fileInput.files = files;
                    this.handleFileSelect({ target: { files } });
                }
            });
        }
    }

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file type
        if (!this.acceptedTypes.includes(file.type)) {
            this.showError('Invalid file type. Please upload a video file (mp4, webm, mov).');
            return;
        }

        // Validate file size
        if (file.size > this.maxSize) {
            this.showError('File is too large. Maximum size is 30MB.');
            return;
        }

        // Show preview
        this.showPreview(file);

        // Upload via AJAX
        this.uploadFile(file);
    }

    showPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            if (this.previewContainer) {
                // Clear existing preview
                this.previewContainer.innerHTML = '';
                
                // Create video element
                const video = document.createElement('video');
                video.src = e.target.result;
                video.className = 'w-full h-full object-cover rounded-xl';
                video.controls = true;
                video.muted = true;
                
                // Create overlay for change button
                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm rounded-xl cursor-pointer';
                overlay.innerHTML = `
                    <div class="flex flex-col items-center text-white">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span class="font-semibold text-sm">Change Video</span>
                    </div>
                `;
                overlay.addEventListener('click', () => this.fileInput.click());
                
                // Create wrapper
                const wrapper = document.createElement('div');
                wrapper.className = 'relative w-full aspect-video rounded-xl overflow-hidden border border-white/5 shadow-sm';
                wrapper.appendChild(video);
                wrapper.appendChild(overlay);
                
                this.previewContainer.appendChild(wrapper);
            }
            
            this.onPreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    uploadFile(file) {
        if (!this.uploadUrl) {
            console.warn('No upload URL provided, skipping AJAX upload');
            return;
        }

        const formData = new FormData();
        formData.append('video', file);
        formData.append('_token', this.csrfToken);

        const xhr = new XMLHttpRequest();
        xhr.timeout = 120000; // 2 minute timeout for video uploads

        // Show progress bar
        if (this.progressContainer) {
            this.progressContainer.innerHTML = `
                <div class="w-full bg-black/20 rounded-full h-2 overflow-hidden">
                    <div class="progress-bar bg-[#DC2626] h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p class="text-xs text-[#A1A1AA] mt-2 text-center">Uploading... <span class="progress-text">0%</span></p>
            `;
        }

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable && this.progressContainer) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                const progressBar = this.progressContainer.querySelector('.progress-bar');
                const progressText = this.progressContainer.querySelector('.progress-text');
                
                if (progressBar) {
                    progressBar.style.width = percentComplete + '%';
                }
                if (progressText) {
                    progressText.textContent = percentComplete + '%';
                }
            }
        });

        xhr.addEventListener('load', () => {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        // Update hidden input with video path
                        if (this.hiddenInput) {
                            this.hiddenInput.value = response.path;
                        }
                        
                        // Hide progress bar
                        if (this.progressContainer) {
                            this.progressContainer.innerHTML = '';
                        }
                        
                        this.onUploadComplete(response);
                    } else {
                        console.error('Upload failed:', response);
                        this.showError(response.message || 'Upload failed');
                    }
                } catch (e) {
                    console.error('Failed to parse response:', xhr.responseText);
                    this.showError('Invalid server response');
                }
            } else {
                console.error('Upload failed with status:', xhr.status, xhr.responseText);
                this.showError('Upload failed. Please try again.');
            }
        });

        xhr.addEventListener('error', () => {
            console.error('Network error during upload');
            this.showError('Network error. Please try again.');
        });

        xhr.addEventListener('timeout', () => {
            console.error('Upload timed out');
            this.showError('Upload timed out. Please try again.');
        });

        xhr.open('POST', this.uploadUrl, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    }

    showError(message) {
        // Hide progress bar
        if (this.progressContainer) {
            this.progressContainer.innerHTML = `
                <p class="text-sm text-[#DC2626] mt-2">${message}</p>
            `;
        }
        
        this.onUploadError(message);
    }
}

// Export for use in Blade templates
if (typeof window !== 'undefined') {
    window.VideoUploader = VideoUploader;
}
