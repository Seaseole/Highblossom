<x-layouts::admin title="SMTP Settings">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">SMTP Settings</h1>
            <p class="text-gray-500 dark:text-gray-400">Configure outgoing email server settings.</p>
        </div>

        <form action="{{ route('admin.smtp.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mailer</label>
                                <select
                                    name="mail_mailer"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                >
                                    <option
                                        value="smtp"
                                        {{ old('mail_mailer', $settings['mail_mailer']) === 'smtp' ? 'selected' : '' }}
                                    >
                                        SMTP
                                    </option>
                                    <option
                                        value="sendmail"
                                        {{ old('mail_mailer', $settings['mail_mailer']) === 'sendmail' ? 'selected' : '' }}
                                    >
                                        Sendmail
                                    </option>
                                    <option
                                        value="log"
                                        {{ old('mail_mailer', $settings['mail_mailer']) === 'log' ? 'selected' : '' }}
                                    >
                                        Log
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Host</label>
                                <input
                                    type="text"
                                    name="mail_host"
                                    value="{{ old('mail_host', $settings['mail_host']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Port</label>
                                <input
                                    type="number"
                                    name="mail_port"
                                    value="{{ old('mail_port', $settings['mail_port']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Encryption</label>
                                <select
                                    name="mail_encryption"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                >
                                    <option
                                        value="tls"
                                        {{ old('mail_encryption', $settings['mail_encryption']) === 'tls' ? 'selected' : '' }}
                                    >
                                        TLS
                                    </option>
                                    <option
                                        value="ssl"
                                        {{ old('mail_encryption', $settings['mail_encryption']) === 'ssl' ? 'selected' : '' }}
                                    >
                                        SSL
                                    </option>
                                    <option
                                        value=""
                                        {{ old('mail_encryption', $settings['mail_encryption']) === '' ? 'selected' : '' }}
                                    >
                                        None
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                <input
                                    type="text"
                                    name="mail_username"
                                    value="{{ old('mail_username', $settings['mail_username']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input
                                    type="password"
                                    name="mail_password"
                                    value="{{ old('mail_password', $settings['mail_password']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">From Address</label>
                                <input
                                    type="email"
                                    name="mail_from_address"
                                    value="{{ old('mail_from_address', $settings['mail_from_address']) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">From Name</label>
                                <input
                                    type="text"
                                    name="mail_from_name"
                                    value="{{ old('mail_from_name', $settings['mail_from_name']) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">Actions</h3>
                        <p class="mb-6 text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                            Saving will update the
                            <code class="font-mono text-gray-900 dark:text-gray-100">.env</code> file directly. You may
                            need to clear the config cache.
                        </p>
                        <button
                            type="submit"
                            class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                        >
                            Save SMTP Settings
                        </button>
                    </div>

                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">Send Test Email</h3>
                        <form action="{{ route('admin.smtp.test') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input
                                    type="email"
                                    name="test_email"
                                    placeholder="recipient@example.com"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                                <button
                                    type="submit"
                                    class="w-full rounded-full border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 dark:border-white/10 dark:text-gray-300 dark:hover:bg-white/5"
                                >
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
