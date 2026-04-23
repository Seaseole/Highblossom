<x-layouts::admin title="SMTP Settings">
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-admin-text font-headline">SMTP Settings</h1>
                <p class="text-admin-text-muted mt-1 text-sm">Configure outgoing email server settings.</p>
            </div>
        </div>

        <form action="{{ route('admin.smtp.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">Mailer</label>
                                <select name="mail_mailer" class="w-full admin-form-input">
                                    <option value="smtp" {{ old('mail_mailer', $settings['mail_mailer']) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ old('mail_mailer', $settings['mail_mailer']) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ old('mail_mailer', $settings['mail_mailer']) === 'log' ? 'selected' : '' }}>Log</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">Host</label>
                                <input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}" class="w-full admin-form-input">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">Port</label>
                                <input type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}" class="w-full admin-form-input">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">Encryption</label>
                                <select name="mail_encryption" class="w-full admin-form-input">
                                    <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ old('mail_encryption', $settings['mail_encryption']) === '' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">Username</label>
                                <input type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}" class="w-full admin-form-input">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">Password</label>
                                <input type="password" name="mail_password" value="{{ old('mail_password', $settings['mail_password']) }}" class="w-full admin-form-input">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">From Address</label>
                                <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required class="w-full admin-form-input">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-admin-text">From Name</label>
                                <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required class="w-full admin-form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 space-y-4">
                        <h3 class="font-bold text-admin-text">Actions</h3>
                        <p class="text-xs text-admin-text-muted leading-relaxed">
                            Saving will update the <code class="text-[#DC2626]">.env</code> file directly. You may need to clear the config cache for changes to take effect immediately.
                        </p>
                        <div class="pt-4 border-t border-admin-border-subtle">
                            <button type="submit" class="w-full py-4 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
                                Save SMTP Settings
                            </button>
                        </div>
                    </div>

                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 space-y-4">
                        <h3 class="font-bold text-admin-text">Send Test Email</h3>
                        <form action="{{ route('admin.smtp.test') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input type="email" name="test_email" placeholder="recipient@example.com" required class="w-full admin-form-input">
                                <button type="submit" class="w-full py-3 admin-action-btn admin-action-btn-ghost">
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
