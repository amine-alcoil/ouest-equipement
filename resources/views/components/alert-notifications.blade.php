<!-- Alert Notifications Container -->
<div id="alertContainer" class="fixed top-20 right-4 z-[9999] max-w-md w-auto space-y-3 pointer-events-none">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="alert-notification pointer-events-auto transform transition-all duration-500 ease-out" 
         data-alert="success"
         style="animation: slideIn 0.5s ease-out forwards;">
        <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl shadow-2xl overflow-hidden border border-emerald-400/30">
            <div class="p-4 flex items-start gap-3">
                <!-- Success Icon -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 pt-0.5">
                    <h4 class="font-semibold text-base mb-1">Succès</h4>
                    <p class="text-sm text-white/95 leading-relaxed">{{ session('success') }}</p>
                </div>
                
                <!-- Close Button -->
                <button onclick="this.closest('.alert-notification').remove()" 
                        class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="h-1 bg-white/20">
                <div class="h-full bg-white/40 alert-progress" style="animation: progress 5s linear;"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
    <div class="alert-notification pointer-events-auto transform transition-all duration-500 ease-out"
         data-alert="error"
         style="animation: slideIn 0.5s ease-out forwards;">
        <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl shadow-2xl overflow-hidden border border-red-400/30">
            <div class="p-4 flex items-start gap-3">
                <!-- Error Icon -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 pt-0.5">
                    <h4 class="font-semibold text-base">Erreur</h4>
                    <p class="text-sm text-white/95 leading-relaxed">{{ session('error') }}</p>
                </div>
                
                <!-- Close Button -->
                <button onclick="this.closest('.alert-notification').remove()" 
                        class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="h-1 bg-white/20">
                <div class="h-full bg-white/40 alert-progress" style="animation: progress 5s linear;"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Warning Alert -->
    @if(session('warning'))
    <div class="alert-notification pointer-events-auto transform transition-all duration-500 ease-out"
         data-alert="warning"
         style="animation: slideIn 0.5s ease-out forwards;">
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl shadow-2xl overflow-hidden border border-amber-400/30">
            <div class="p-4 flex items-start gap-3">
                <!-- Warning Icon -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 pt-0.5">
                    <h4 class="font-semibold text-base mb-1">Attention</h4>
                    <p class="text-sm text-white/95 leading-relaxed">{{ session('warning') }}</p>
                </div>
                
                <!-- Close Button -->
                <button onclick="this.closest('.alert-notification').remove()" 
                        class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="h-1 bg-white/20">
                <div class="h-full bg-white/40 alert-progress" style="animation: progress 5s linear;"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Info Alert -->
    @if(session('info'))
    <div class="alert-notification pointer-events-auto transform transition-all duration-500 ease-out"
         data-alert="info"
         style="animation: slideIn 0.5s ease-out forwards;">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-2xl overflow-hidden border border-blue-400/30">
            <div class="p-4 flex items-start gap-3">
                <!-- Info Icon -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 pt-0.5">
                    <h4 class="font-semibold text-base mb-1">Information</h4>
                    <p class="text-sm text-white/95 leading-relaxed">{{ session('info') }}</p>
                </div>
                
                <!-- Close Button -->
                <button onclick="this.closest('.alert-notification').remove()" 
                        class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="h-1 bg-white/20">
                <div class="h-full bg-white/40 alert-progress" style="animation: progress 5s linear;"></div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
    
    @keyframes progress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
    
    .alert-notification {
        transition: all 0.3s ease-out;
    }
</style>

<script>
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-notification');
        
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'slideOut 0.5s ease-out forwards';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    });
    
    // JavaScript function to show alerts dynamically
    window.showAlert = function(type, message) {
        const container = document.getElementById('alertContainer');
        if (!container) return;
        
        const colors = {
            success: { from: 'emerald-500', to: 'green-600', border: 'emerald-400/30' },
            error: { from: 'red-500', to: 'rose-600', border: 'red-400/30' },
            warning: { from: 'amber-500', to: 'orange-600', border: 'amber-400/30' },
            info: { from: 'blue-500', to: 'indigo-600', border: 'blue-400/30' }
        };
        
        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        };
        
        const titles = {
            success: 'Succès',
            error: 'Erreur',
            warning: 'Attention',
            info: 'Information'
        };
        
        const color = colors[type] || colors.info;
        const icon = icons[type] || icons.info;
        const title = titles[type] || titles.info;
        
        const alert = document.createElement('div');
        alert.className = 'alert-notification pointer-events-auto';
        alert.setAttribute('data-alert', type);
        alert.style.animation = 'slideIn 0.5s ease-out forwards';
        
        alert.innerHTML = `
            <div class="bg-gradient-to-r from-${color.from} to-${color.to} text-white rounded-xl shadow-2xl overflow-hidden border border-${color.border}">
                <div class="p-4 flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${icon}
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <h4 class="font-semibold text-base${type === 'error' ? '' : ' mb-1'}">${title}</h4>
                        ${type !== 'error' ? `<p class="text-sm text-white/95 leading-relaxed">${message}</p>` : ''}
                    </div>
                    <button onclick="this.closest('.alert-notification').remove()" 
                            class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="h-1 bg-white/20">
                    <div class="h-full bg-white/40" style="animation: progress 5s linear;"></div>
                </div>
            </div>
        `;
        
        container.appendChild(alert);
        
        setTimeout(() => {
            alert.style.animation = 'slideOut 0.5s ease-out forwards';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    };
</script>