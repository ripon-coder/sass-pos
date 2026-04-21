<x-layouts.admin pageTitle="Settings">

<div class="p-6 space-y-6" x-data="settingsPage()">

    {{-- Page Header --}}
    <div>
        <h2 class="text-lg font-semibold text-slate-800">Settings</h2>
        <p class="text-sm text-slate-500 mt-0.5">Manage your store configuration, users, and permissions</p>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-slate-200">
        <nav class="flex gap-6" aria-label="Settings tabs">
            <template x-for="tab in tabs" :key="tab.key">
                <button
                    @click="activeTab = tab.key"
                    class="pb-3 text-sm font-medium border-b-2 transition-colors -mb-px"
                    :class="activeTab === tab.key ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    x-text="tab.label"
                ></button>
            </template>
        </nav>
    </div>

    {{-- ===== TAB: Store Settings ===== --}}
    <div x-show="activeTab === 'store'" x-cloak>
        <form @submit.prevent="saveStore()" class="max-w-2xl space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Store Information</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label" for="store-name-input">Store Name</label>
                            <input type="text" id="store-name-input" x-model="store.name" class="form-input" placeholder="Acme Store">
                        </div>
                        <div>
                            <label class="form-label" for="store-email-input">Contact Email</label>
                            <input type="email" id="store-email-input" x-model="store.email" class="form-input" placeholder="hello@acme.com">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label" for="store-phone-input">Phone</label>
                            <input type="tel" id="store-phone-input" x-model="store.phone" class="form-input" placeholder="+1 (555) 000-0000">
                        </div>
                        <div>
                            <label class="form-label" for="store-currency-input">Currency</label>
                            <select id="store-currency-input" x-model="store.currency" class="form-input form-select">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                                <option value="BDT">BDT (৳)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="store-address-input">Address</label>
                        <textarea id="store-address-input" x-model="store.address" class="form-input" rows="2" placeholder="123 Main St, City, State, ZIP"></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Tax & Receipt</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label" for="store-tax-input">Tax Rate (%)</label>
                            <input type="number" id="store-tax-input" x-model.number="store.taxRate" class="form-input" min="0" max="100" step="0.01">
                        </div>
                        <div>
                            <label class="form-label" for="store-taxid-input">Tax ID / VAT Number</label>
                            <input type="text" id="store-taxid-input" x-model="store.taxId" class="form-input" placeholder="XX-XXXXXXX">
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="store-receipt-footer">Receipt Footer</label>
                        <textarea id="store-receipt-footer" x-model="store.receiptFooter" class="form-input" rows="2" placeholder="Thank you for your purchase!"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary" id="save-store-btn">Save Changes</button>
            </div>
        </form>
    </div>

    {{-- ===== TAB: Users ===== --}}
    <div x-show="activeTab === 'users'" x-cloak>
        <div class="max-w-4xl space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Manage team members who have access to this store</p>
                <button @click="userModal.open = true; userModal.editing = false; userModal.form = {name:'',email:'',role:'staff'}" class="btn btn-primary btn-sm" id="add-user-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Invite User
                </button>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table class="table" id="users-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="u in users" :key="u.id">
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
                                                :class="u.avatarColor"
                                                x-text="u.name.split(' ').map(n => n[0]).join('')"
                                            ></div>
                                            <div>
                                                <p class="font-medium text-slate-700" x-text="u.name"></p>
                                                <p class="text-xs text-slate-400" x-text="u.email"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge"
                                            :class="u.role === 'admin' ? 'badge-info' : 'badge-gray'"
                                            x-text="u.role.charAt(0).toUpperCase() + u.role.slice(1)"
                                        ></span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full" :class="u.active ? 'bg-green-500' : 'bg-slate-300'"></div>
                                            <span class="text-xs text-slate-500" x-text="u.active ? 'Active' : 'Offline'"></span>
                                        </div>
                                    </td>
                                    <td class="text-xs text-slate-400" x-text="u.lastActive"></td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <button @click="userModal.open = true; userModal.editing = true; userModal.form = {...u}" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-blue-600" title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                            </button>
                                            <button x-show="u.role !== 'admin' || users.filter(x => x.role === 'admin').length > 1" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-red-500" title="Remove">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB: Roles & Permissions ===== --}}
    <div x-show="activeTab === 'roles'" x-cloak>
        <div class="max-w-3xl space-y-4">
            <p class="text-sm text-slate-500">Define what each role can access in the system</p>

            <template x-for="role in roles" :key="role.name">
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-semibold text-slate-800" x-text="role.name"></h3>
                            <span class="badge badge-gray" x-text="role.description"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <template x-for="perm in permissions" :key="perm.key">
                                <label class="flex items-center gap-2.5 py-1 cursor-pointer group">
                                    <input
                                        type="checkbox"
                                        :checked="role.permissions.includes(perm.key)"
                                        @change="togglePermission(role, perm.key)"
                                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                        :disabled="role.name === 'Admin'"
                                    >
                                    <span class="text-sm text-slate-600 group-hover:text-slate-800" x-text="perm.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <div class="flex justify-end">
                <button @click="saveRoles()" class="btn btn-primary" id="save-roles-btn">Save Permissions</button>
            </div>
        </div>
    </div>

    {{-- ===== USER MODAL ===== --}}
    <div x-show="userModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="userModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="user-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800" x-text="userModal.editing ? 'Edit User' : 'Invite User'"></h2>
                <button @click="userModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveUser()" class="p-6 space-y-4">
                <div>
                    <label class="form-label">Full Name</label>
                    <input type="text" x-model="userModal.form.name" class="form-input" placeholder="John Doe" required>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" x-model="userModal.form.email" class="form-input" placeholder="john@acme.com" required>
                </div>
                <div>
                    <label class="form-label">Role</label>
                    <select x-model="userModal.form.role" class="form-input form-select">
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="cashier">Cashier</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="userModal.open = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" x-text="userModal.editing ? 'Save' : 'Send Invite'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function settingsPage() {
    return {
        activeTab: 'store',
        tabs: [
            { key: 'store', label: 'Store Settings' },
            { key: 'users', label: 'User Management' },
            { key: 'roles', label: 'Roles & Permissions' },
        ],
        store: {
            name: 'Acme Store',
            email: 'hello@acmestore.com',
            phone: '+1 (555) 123-4567',
            currency: 'USD',
            address: '123 Main Street, New York, NY 10001',
            taxRate: 8.5,
            taxId: '12-3456789',
            receiptFooter: 'Thank you for shopping at Acme Store!',
        },
        users: [
            { id: 1, name: 'John Doe',     email: 'john@acme.com',   role: 'admin',   avatarColor: 'bg-blue-100 text-blue-600',    active: true,  lastActive: 'Now' },
            { id: 2, name: 'Jane Smith',   email: 'jane@acme.com',   role: 'staff',   avatarColor: 'bg-violet-100 text-violet-600', active: true,  lastActive: '5m ago' },
            { id: 3, name: 'Mark Wilson',  email: 'mark@acme.com',   role: 'cashier', avatarColor: 'bg-emerald-100 text-emerald-600', active: false, lastActive: 'Yesterday' },
            { id: 4, name: 'Emily Chen',   email: 'emily@acme.com',  role: 'staff',   avatarColor: 'bg-amber-100 text-amber-600',  active: false, lastActive: '2 days ago' },
        ],
        userModal: { open: false, editing: false, form: {} },
        permissions: [
            { key: 'pos',              label: 'POS Screen' },
            { key: 'products.view',    label: 'View Products' },
            { key: 'products.manage',  label: 'Manage Products' },
            { key: 'orders.view',      label: 'View Orders' },
            { key: 'orders.refund',    label: 'Refund Orders' },
            { key: 'customers.view',   label: 'View Customers' },
            { key: 'customers.manage', label: 'Manage Customers' },
            { key: 'reports',          label: 'View Reports' },
            { key: 'settings',         label: 'Settings' },
        ],
        roles: [
            {
                name: 'Admin',
                description: 'Full access to everything',
                permissions: ['pos','products.view','products.manage','orders.view','orders.refund','customers.view','customers.manage','reports','settings'],
            },
            {
                name: 'Staff',
                description: 'Manage day-to-day operations',
                permissions: ['pos','products.view','products.manage','orders.view','customers.view','customers.manage'],
            },
            {
                name: 'Cashier',
                description: 'POS and order access only',
                permissions: ['pos','orders.view','customers.view'],
            },
        ],

        togglePermission(role, key) {
            if (role.name === 'Admin') return;
            const idx = role.permissions.indexOf(key);
            if (idx > -1) role.permissions.splice(idx, 1);
            else role.permissions.push(key);
        },

        saveStore() {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Store settings saved.', type: 'success' } }));
        },
        saveUser() {
            if (!this.userModal.editing) {
                this.users.push({ ...this.userModal.form, id: Date.now(), avatarColor: 'bg-slate-100 text-slate-600', active: false, lastActive: 'Invited' });
            } else {
                const idx = this.users.findIndex(u => u.id === this.userModal.form.id);
                if (idx > -1) this.users[idx] = { ...this.users[idx], ...this.userModal.form };
            }
            this.userModal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: this.userModal.editing ? 'User updated.' : 'Invitation sent.', type: 'success' } }));
        },
        saveRoles() {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Permissions saved.', type: 'success' } }));
        },
    };
}
</script>

</x-layouts.admin>
