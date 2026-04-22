<x-layouts::admin title="SMTP Settings">
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#FAFAFA] font-headline">SMTP Settings</h1>
                <p class="text-[#A1A1AA] mt-1 text-sm">Configure outgoing email server settings.</p>
            </div>
        </div>

        <form action="{{ route('admin.smtp.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">Mailer</label>
                                <select name="mail_mailer" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                    <option value="smtp" {{ old('mail_mailer', $settings['mail_mailer']) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ old('mail_mailer', $settings['mail_mailer']) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ old('mail_mailer', $settings['mail_mailer']) === 'log' ? 'selected' : '' }}>Log</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">Host</label>
                                <input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">Port</label>
                                <input type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">Encryption</label>
                                <select name="mail_encryption" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                    <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ old('mail_encryption', $settings['mail_encryption']) === '' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">Username</label>
                                <input type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">Password</label>
                                <input type="password" name="mail_password" value="{{ old('mail_password', $settings['mail_password']) }}" class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">From Address</label>
                                <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-[#FAFAFA]">From Name</label>
                                <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-4">
                        <h3 class="font-bold text-[#FAFAFA]">Actions</h3>
                        <p class="text-xs text-[#A1A1AA] leading-relaxed">
                            Saving will update the <code class="text-[#DC2626]">.env</code> file directly. You may need to clear the config cache for changes to take effect immediately.
                        </p>
                        <div class="pt-4 border-t border-white/5">
                            <button type="submit" class="w-full py-4 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
                                Save SMTP Settings
                            </button>
                        </div>
                    </div>

                    <div class="bg-[#16161d] rounded-2xl border border-white/5 p-6 space-y-4">
                        <h3 class="font-bold text-[#FAFAFA]">Send Test Email</h3>
                        <form action="{{ route('admin.smtp.test') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input type="email" name="test_email" placeholder="recipient@example.com" required class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-[#FAFAFA] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626] transition-all">
                                <button type="submit" class="w-full py-3 bg-[#16161d] border border-white/10 hover:bg-white/5 text-[#FAFAFA] font-medium rounded-xl transition-all active:scale-[0.98]">
                                    Send Test
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
