<div x-data="helpdeskWidget()" class="fixed z-[80]" x-init="posX = window.innerWidth / 2 - 50"
     :style="'top:' + posY + 'px; left:' + posX + 'px'">
    <button @mousedown="startDrag($event)" @touchstart.passive="startDrag($event)" @click="togglePopup()" 
            class="px-5 h-10 rounded-full flex items-center justify-center gap-2 text-white font-black tracking-wider transition-all cursor-pointer"
            style="background: linear-gradient(135deg, #dc2626, #e11d48); box-shadow: 0 0 15px rgba(220,38,38,0.6); border: 2px solid rgba(255,255,255,0.8); animation: helpdesk-pulse 2s infinite;">
        <i data-lucide="life-buoy" class="w-5 h-5"></i>
        HELP
    </button>
    
    {{-- Helpdesk Modal --}}
    <div x-show="showPopup" x-transition.opacity class="fixed inset-0 z-[90] flex items-center justify-center p-4" style="display:none" @click.self="showPopup=false">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md shadow-2xl border border-gray-200 dark:border-gray-800" @click.stop>
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <i data-lucide="life-buoy" class="w-5 h-5 text-blue-500"></i> Help / Support
                </h2>
                <button @click="showPopup=false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form @submit.prevent="submitHelpdesk" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Request Type *</label>
                    <select x-model="form.type" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="suggestion">Suggestion (सुझाव)</option>
                        <option value="grievance">Grievance (तक्रार)</option>
                        <option value="help">Help (मदत)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject (विषय)</label>
                    <input type="text" x-model="form.subject" placeholder="Short description" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message (संदेश) *</label>
                    <textarea x-model="form.message" required rows="3" placeholder="Explain your issue or suggestion here..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachment (Photo/File)</label>
                    <input type="file" x-ref="attachment" class="w-full px-4 py-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                
                <div class="pt-2">
                    <button type="submit" :disabled="isBusy" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition disabled:opacity-50 flex justify-center items-center gap-2">
                        <span x-show="!isBusy">Submit Request</span>
                        <span x-show="isBusy">Submitting...</span>
                        <i x-show="!isBusy" data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('helpdesk.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">View My Previous Tickets</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function helpdeskWidget() {
    return {
        showPopup: false,
        posX: 0, posY: 20,
        dragging: false, dragStartX: 0, dragStartY: 0, startPosX: 0, startPosY: 0, hasMoved: false,
        isBusy: false,
        
        form: {
            type: 'help',
            subject: '',
            message: ''
        },

        togglePopup() { 
            if (!this.hasMoved) this.showPopup = !this.showPopup; 
            this.hasMoved = false; 
        },

        async submitHelpdesk() {
            if (!this.form.message) return;
            this.isBusy = true;
            
            let formData = new FormData();
            formData.append('type', this.form.type);
            formData.append('subject', this.form.subject);
            formData.append('message', this.form.message);
            
            if (this.$refs.attachment.files[0]) {
                formData.append('attachment', this.$refs.attachment.files[0]);
            }
            
            try {
                let res = await fetch('{{ route("helpdesk.submit") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let data = await res.json();
                if (data.success) {
                    alert('Your request has been submitted successfully!');
                    this.showPopup = false;
                    this.form.subject = '';
                    this.form.message = '';
                    this.$refs.attachment.value = '';
                } else {
                    alert(data.message || 'Error submitting request');
                }
            } catch(e) {
                alert('Network error. Please try again.');
            }
            this.isBusy = false;
        },

        startDrag(e) {
            this.dragging = true; this.hasMoved = false;
            var touch = e.touches ? e.touches[0] : e;
            this.dragStartX = touch.clientX; this.dragStartY = touch.clientY;
            this.startPosX = this.posX; this.startPosY = this.posY;
            var self = this;
            var onMove = function(ev) {
                var t = ev.touches ? ev.touches[0] : ev;
                var dx = t.clientX - self.dragStartX; var dy = t.clientY - self.dragStartY;
                if (Math.abs(dx)>3||Math.abs(dy)>3) self.hasMoved = true;
                self.posX = Math.max(8, Math.min(window.innerWidth-100, self.startPosX+dx));
                self.posY = Math.max(8, Math.min(window.innerHeight-50, self.startPosY+dy));
            };
            var onEnd = function() {
                self.dragging = false;
                document.removeEventListener('mousemove',onMove); document.removeEventListener('mouseup',onEnd);
                document.removeEventListener('touchmove',onMove); document.removeEventListener('touchend',onEnd);
            };
            document.addEventListener('mousemove',onMove); document.addEventListener('mouseup',onEnd);
            document.addEventListener('touchmove',onMove,{passive:true}); document.addEventListener('touchend',onEnd);
        }
    }
}
</script>

<style>
@keyframes helpdesk-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.9; transform: scale(1.05); }
}
</style>
