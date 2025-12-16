@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="page-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div>
                    <h1>Kelola User</h1>
                    <p class="subtitle">Manage user accounts and permissions</p>
                </div>
            </div>
            <button class="btn-add" onclick="document.getElementById('dialogTambahUser').showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah User
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon admin">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $users->where('role', 'Admin')->count() }}</h3>
                <p>Admin</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon dispatcher">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $users->where('role', 'Dispatcher')->count() }}</h3>
                <p>Dispatcher</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teknisi">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $users->where('role', 'Teknisi')->count() }}</h3>
                <p>Teknisi</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon manajer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $users->where('role', 'Manajer')->count() }}</h3>
                <p>Manajer</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="filter-section">
        <div class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari user berdasarkan nama, username, atau email...">
        </div>
        <select id="roleFilter" class="filter-select">
            <option value="">Semua Role</option>
            <option value="Admin">Admin</option>
            <option value="Dispatcher">Dispatcher</option>
            <option value="Teknisi">Teknisi</option>
            <option value="Manajer">Manajer</option>
        </select>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>
                        <div class="th-content">
                            <span>ID</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-content">
                            <span>Nama</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-content">
                            <span>Username</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @foreach($users as $u)
                <tr class="user-row" data-user-id="{{ $u->id_user }}">
                    <td><span class="id-badge">#{{ $u->id_user }}</span></td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>
                            <span class="username">{{ $u->username }}</span>
                    </td>
                    <td>
                        <div class="email-cell">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            {{ $u->email }}
                        </div>
                    </td>
                    <td>
                        <span class="role-badge role-{{ strtolower($u->role) }}">{{ $u->role }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-edit" 
                                    onclick="document.getElementById('dialogEditUser{{ $u->id_user }}').showModal()"
                                    title="Edit User">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('admin.user.delete', $u->id_user) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="btn-action btn-delete" 
                                        type="button"
                                        onclick="confirmDelete(this)"
                                        title="Hapus User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- DIALOG EDIT USER --}}
                <dialog id="dialogEditUser{{ $u->id_user }}" class="modern-dialog">
                    <form method="POST" action="{{ route('admin.user.update', $u->id_user) }}" class="dialog-form">
                        @csrf
                        @method('PUT')
                        <div class="dialog-header">
                            <div class="dialog-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <h5>Edit User</h5>
                            </div>
                            <button type="button" class="btn-close" onclick="document.getElementById('dialogEditUser{{ $u->id_user }}').close()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="dialog-body">
                            <div class="form-group">
                                <label for="edit_name_{{ $u->id_user }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    Nama Lengkap
                                </label>
                                <input type="text" 
                                       id="edit_name_{{ $u->id_user }}"
                                       class="form-input" 
                                       name="name"
                                       value="{{ $u->name }}" 
                                       placeholder="Masukkan nama lengkap"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="edit_role_{{ $u->id_user }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                    </svg>
                                    Role
                                </label>
                                <select id="edit_role_{{ $u->id_user }}" class="form-input" name="role" required>
                                    <option value="Admin" {{ $u->role=='Admin'?'selected':'' }}>Admin</option>
                                    <option value="Dispatcher" {{ $u->role=='Dispatcher'?'selected':'' }}>Dispatcher</option>
                                    <option value="Teknisi" {{ $u->role=='Teknisi'?'selected':'' }}>Teknisi</option>
                                    <option value="Manajer" {{ $u->role=='Manajer'?'selected':'' }}>Manajer</option>
                                </select>
                            </div>
                        </div>
                        <div class="dialog-footer">
                            <button type="button" class="btn-secondary" onclick="document.getElementById('dialogEditUser{{ $u->id_user }}').close()">
                                Batal
                            </button>
                            <button type="submit" class="btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </dialog>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- DIALOG TAMBAH USER --}}
<dialog id="dialogTambahUser" class="modern-dialog">
    <form method="POST" action="{{ route('admin.user.store') }}" class="dialog-form">
        @csrf
        <div class="dialog-header">
            <div class="dialog-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
                <h5>Tambah User Baru</h5>
            </div>
            <button type="button" class="btn-close" onclick="document.getElementById('dialogTambahUser').close()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="dialog-body">
            <div class="form-group">
                <label for="add_username">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Username
                </label>
                <input type="text" 
                       id="add_username"
                       class="form-input" 
                       name="username" 
                       placeholder="Masukkan username"
                       required>
            </div>
            <div class="form-group">
                <label for="add_name">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Nama Lengkap
                </label>
                <input type="text" 
                       id="add_name"
                       class="form-input" 
                       name="name" 
                       placeholder="Masukkan nama lengkap"
                       required>
            </div>
            <div class="form-group">
                <label for="add_email">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    Email
                </label>
                <input type="email" 
                       id="add_email"
                       class="form-input" 
                       name="email" 
                       placeholder="Masukkan email"
                       required>
            </div>
            <div class="form-group">
                <label for="add_role">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    Role
                </label>
                <select id="add_role" class="form-input" name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Dispatcher">Dispatcher</option>
                    <option value="Teknisi">Teknisi</option>
                    <option value="Manajer">Manajer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="add_password">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Password
                </label>
                <div class="password-input-wrapper">
                    <input type="password" 
                           id="add_password"
                           class="form-input" 
                           name="password" 
                           placeholder="Masukkan password"
                           required>
                    <button type="button" class="toggle-password" onclick="togglePassword('add_password')">
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-closed" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="dialog-footer">
            <button type="button" class="btn-secondary" onclick="document.getElementById('dialogTambahUser').close()">
                Batal
            </button>
            <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Tambah User
            </button>
        </div>
    </form>
</dialog>

{{-- Delete Confirmation Dialog --}}
<dialog id="dialogConfirmDelete" class="modern-dialog confirm-dialog">
    <div class="confirm-content">
        <div class="confirm-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h3>Konfirmasi Hapus User</h3>
        <p>Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="confirm-actions">
            <button type="button" class="btn-secondary" onclick="document.getElementById('dialogConfirmDelete').close()">
                Batal
            </button>
            <button type="button" class="btn-danger" id="confirmDeleteBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Ya, Hapus
            </button>
        </div>
    </div>
</dialog>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #dc2626;
        --primary-dark: #b91c1c;
        --secondary: #64748b;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --light: #f8fafc;
        --dark: #0f172a;
        --border: #fee2e2;
        --shadow: rgba(220, 38, 38, 0.1);
        --shadow-lg: rgba(220, 38, 38, 0.15);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #e5e7eb;
        min-height: 100vh;
        padding: 0;
        margin: 0;
    }

    .page-container {
        max-width: 100%;
        margin: 0;
        padding: 1 rem;
        animation: fadeIn 0.5s ease;
        min-height: 100vh;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .icon-wrapper {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .page-header h1 {
        font-size: 1.75rem;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .subtitle {
        color: var(--secondary);
        font-size: 0.95rem;
    }

    .btn-add {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.admin {
        background: linear-gradient(135deg, #dc2626, #991b1b);
    }

    .stat-icon.dispatcher {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-icon.teknisi {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-icon.manajer {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-content h3 {
        font-size: 2rem;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .stat-content p {
        color: var(--secondary);
        font-size: 0.9rem;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-box svg {
        position: absolute;
        left: 1rem;
        color: var(--secondary);
    }

    .search-box input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .filter-select {
        padding: 0.875rem 1.25rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    /* Table */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }

    .modern-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .th-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .sort-icon {
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .th-content:hover .sort-icon {
        opacity: 1;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }

    .modern-table td {
        padding: 1rem;
        font-size: 0.95rem;
    }

    .id-badge {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .username {
        font-weight: 600;
        color: var(--dark);
    }

    .email-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--secondary);
    }

    .role-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .role-admin {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }

    .role-dispatcher {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .role-teknisi {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .role-manajer {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #fef3c7;
        color: #f59e0b;
    }

    .btn-edit:hover {
        background: #fcd34d;
        transform: scale(1.1);
    }

    .btn-delete {
        background: #fee2e2;
        color: #ef4444;
    }

    .btn-delete:hover {
        background: #fca5a5;
        transform: scale(1.1);
    }

    /* Dialog */
    .modern-dialog {
        border: none;
        border-radius: 16px;
        padding: 0;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: dialogSlideIn 0.3s ease;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: 0;
    }

    @keyframes dialogSlideIn {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    .modern-dialog::backdrop {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: none;
    }

    .dialog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .dialog-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--primary);
    }

    .dialog-title h5 {
        margin: 0;
        font-size: 1.25rem;
        color: var(--dark);
    }

    .btn-close {
        width: 36px;
        height: 36px;
        border: none;
        background: #f1f5f9;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary);
        transition: all 0.3s ease;
    }

    .btn-close:hover {
        background: #e2e8f0;
        color: var(--dark);
        transform: rotate(90deg);
    }

    .dialog-body {
        padding: 1.5rem;
        max-height: 60vh;
        overflow-y: auto;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        color: var(--dark);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .password-input-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--secondary);
        padding: 0.5rem;
        display: flex;
        align-items: center;
        transition: color 0.3s ease;
    }

    .toggle-password:hover {
        color: var(--primary);
    }

    .dialog-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1.5rem;
        border-top: 1px solid var(--border);
        background: #f8fafc;
    }

    .btn-primary, .btn-secondary, .btn-danger {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: var(--secondary);
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    /* Confirm Dialog */
    .confirm-dialog {
        max-width: 400px;
    }

    .confirm-content {
        padding: 2rem;
        text-align: center;
    }

    .confirm-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .confirm-content h3 {
        font-size: 1.5rem;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }

    .confirm-content p {
        color: var(--secondary);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .confirm-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        body {
            padding: 0;
        }

        .page-container {
            margin-left: 0;
            padding: 1rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .filter-section {
            flex-direction: column;
        }

        .search-box {
            min-width: 100%;
        }

        .table-container {
            overflow-x: auto;
        }

        .modern-table {
            min-width: 800px;
        }
    }

    /* Loading Animation */
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }

    .loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterTable();
    });

    // Role filter
    document.getElementById('roleFilter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value;
        const rows = document.querySelectorAll('.user-row');

        rows.forEach(row => {
            const username = row.querySelector('.username').textContent.toLowerCase();
            const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const email = row.querySelector('.email-cell').textContent.toLowerCase();
            const role = row.querySelector('.role-badge').textContent;

            const matchesSearch = username.includes(searchTerm) || 
                                name.includes(searchTerm) || 
                                email.includes(searchTerm);
            const matchesRole = !roleFilter || role === roleFilter;

            if (matchesSearch && matchesRole) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Note: Dialog click outside to close is disabled to keep sidebar visible
    // Users can close dialog using close button or ESC key

    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = event.currentTarget;
        const eyeOpen = button.querySelector('.eye-open');
        const eyeClosed = button.querySelector('.eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        } else {
            input.type = 'password';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        }
    }

    // Confirm delete
    let deleteFormToSubmit = null;

    function confirmDelete(button) {
        deleteFormToSubmit = button.closest('.delete-form');
        document.getElementById('dialogConfirmDelete').showModal();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteFormToSubmit) {
            deleteFormToSubmit.submit();
        }
    });

    // Table sorting (basic implementation)
    document.querySelectorAll('.th-content').forEach(th => {
        th.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const index = Array.from(this.closest('tr').children).indexOf(this.closest('th'));
            
            // Simple toggle sort
            rows.sort((a, b) => {
                const aText = a.children[index].textContent.trim();
                const bText = b.children[index].textContent.trim();
                return aText.localeCompare(bText);
            });

            // Reverse on second click
            if (this.dataset.sorted === 'asc') {
                rows.reverse();
                this.dataset.sorted = 'desc';
            } else {
                this.dataset.sorted = 'asc';
            }

            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // Add hover effect on table rows
    document.querySelectorAll('.user-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Form validation with visual feedback
    document.querySelectorAll('.dialog-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('.form-input[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'var(--danger)';
                    input.style.animation = 'shake 0.5s';
                } else {
                    input.style.borderColor = 'var(--success)';
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    // Shake animation for validation errors
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    `;
    document.head.appendChild(style);

    // Auto-hide success messages
    window.addEventListener('load', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'fadeOut 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>
@endsection