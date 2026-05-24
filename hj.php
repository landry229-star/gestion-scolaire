<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Étudiant - Vue 3 SPA</title>
    <!-- Tailwind CSS pour un design moderne et responsive -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Intégration globale de Vue 3 (Composition API) -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Styles personnalisés reprenant vos classes utilitaires Blade */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        .nav-link.active {
            background-color: #ffffff;
            color: #4f46e5; /* Indigo 600 */
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .nav-link:not(.active) {
            color: #e0e7ff; /* Indigo 100 */
        }
        .card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
            padding: 24px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 12px;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 12px;
            transition: background-color 0.2s;
        }
        .btn-secondary:hover {
            background-color: #f8fafc;
        }
        .stat-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        /* Style d'animation fluide de transition de page */
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.2s, transform 0.2s;
        }
        .fade-enter-from {
            opacity: 0;
            transform: translateY(10px);
        }
        .fade-leave-to {
            opacity: 0;
            transform: translateY(-10px);
        }
    </style>
</head>
<body class="bg-slate-50 font-sans">

<div id="app" class="min-h-screen flex flex-col lg:flex-row">

    <!-- ==================== SIDEBAR DESKTOP (Layout Persistant) ==================== -->
    <aside class="hidden lg:flex flex-col w-64 bg-gradient-to-b from-indigo-700 to-indigo-800 shrink-0 fixed h-full z-30">
        <!-- Logo -->
        <div class="px-6 py-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="graduation-cap" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-white leading-none">Mon Espace</p>
                    <p class="text-xs text-indigo-300 mt-1">Groupe 27</p>
                </div>
            </div>
        </div>

        <!-- Profil Étudiant Réactif -->
        <div class="px-4 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ getInitials(student.nom_complet) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ student.nom_complet }}</p>
                    <p class="text-xs text-indigo-300 truncate font-mono">{{ student.matricule }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Réactive (Sans rechargement DOM) -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <!-- Tableau de bord -->
            <div @click="setRoute('dashboard')" :class="['nav-link', currentRoute === 'dashboard' ? 'active' : '']">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i> Tableau de bord
            </div>

            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-indigo-400 uppercase tracking-wider">Scolarité</p>

            <div @click="setRoute('inscription')" :class="['nav-link', currentRoute === 'inscription' ? 'active' : '']">
                <i data-lucide="clipboard-list" class="w-4 h-4 shrink-0"></i> Mon inscription
            </div>

            <div @click="setRoute('reinscription')" :class="['nav-link', currentRoute === 'reinscription' ? 'active' : '']">
                <i data-lucide="refresh-cw" class="w-4 h-4 shrink-0"></i> Ré-inscription
            </div>

            <div @click="setRoute('documents')" :class="['nav-link', currentRoute === 'documents' ? 'active' : '']">
                <i data-lucide="folder-open" class="w-4 h-4 shrink-0"></i> Mes documents
            </div>

            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-indigo-400 uppercase tracking-wider">Pédagogie</p>

            <div @click="setRoute('notes')" :class="['nav-link', currentRoute === 'notes' ? 'active' : '']">
                <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i> Mes notes
            </div>

            <div @click="setRoute('edt')" :class="['nav-link', currentRoute === 'edt' ? 'active' : '']">
                <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i> Emploi du temps
            </div>

            <div @click="setRoute('presences')" :class="['nav-link', currentRoute === 'presences' ? 'active' : '']">
                <i data-lucide="check-square" class="w-4 h-4 shrink-0"></i> Mes présences
            </div>

            <p class="px-4 pt-4 pb-1 text-xs font-semibold text-indigo-400 uppercase tracking-wider">Informations</p>

            <div @click="setRoute('annonces')" :class="['nav-link', currentRoute === 'annonces' ? 'active' : '']">
                <i data-lucide="megaphone" class="w-4 h-4 shrink-0"></i> Annonces
                <span v-if="unreadAnnoncesCount > 0" class="ml-auto bg-white/20 text-white text-xs px-1.5 py-0.5 rounded-full">
                    {{ unreadAnnoncesCount }}
                </span>
            </div>

            <div @click="setRoute('notifications')" :class="['nav-link', currentRoute === 'notifications' ? 'active' : '']">
                <i data-lucide="bell" class="w-4 h-4 shrink-0"></i> Notifications
                <span v-if="unreadNotifsCount > 0" class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                    {{ unreadNotifsCount }}
                </span>
            </div>
        </nav>

        <!-- Déconnexion Simulée -->
        <div class="px-4 py-4 border-t border-white/10">
            <div @click="handleLogout" class="nav-link w-full hover:bg-red-500/20 text-red-100">
                <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i> Déconnexion
            </div>
        </div>
    </aside>

    <!-- ==================== CONTENU PRINCIPAL & ROUTER VUE ==================== -->
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

        <!-- Topbar mobile réactive -->
        <header class="lg:hidden flex items-center justify-between px-4 py-3 bg-indigo-700 text-white sticky top-0 z-20 shadow-md">
            <div class="flex items-center gap-3">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-1.5 hover:bg-white/10 rounded-lg">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <p class="text-sm font-semibold">{{ currentRouteTitle }}</p>
            </div>
            <div class="flex items-center gap-2">
                <div v-if="unreadNotifsCount > 0" @click="setRoute('notifications')" class="relative p-1.5 cursor-pointer">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold">
                        {{ unreadNotifsCount }}
                    </span>
                </div>
            </div>
        </header>

        <!-- Menu Mobile Overlay -->
        <div v-if="mobileMenuOpen" @click="mobileMenuOpen = false" class="lg:hidden fixed inset-0 bg-black/50 z-40"></div>

        <!-- Sidebar Drawer Mobile -->
        <aside v-if="mobileMenuOpen" class="lg:hidden fixed left-0 top-0 bottom-0 w-64 bg-indigo-800 z-50 overflow-y-auto flex flex-col">
            <div class="px-4 py-5 border-b border-white/10 flex items-center justify-between">
                <p class="text-sm font-bold text-white">Mon Espace</p>
                <button @click="mobileMenuOpen = false" class="text-white hover:bg-white/10 p-1 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="px-3 py-4 space-y-1 flex-1">
                <div v-for="item in navItems" :key="item.route"
                     @click="setRoute(item.route)"
                     :class="['nav-link', currentRoute === item.route ? 'active' : '']">
                    <i :data-lucide="item.icon" class="w-4 h-4 shrink-0"></i> {{ item.label }}
                    <span v-if="item.route === 'notifications' && unreadNotifsCount > 0" class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                        {{ unreadNotifsCount }}
                    </span>
                    <span v-if="item.route === 'annonces' && unreadAnnoncesCount > 0" class="ml-auto bg-white/20 text-white text-xs px-1.5 py-0.5 rounded-full">
                        {{ unreadAnnoncesCount }}
                    </span>
                </div>
            </nav>
            <div class="p-4 border-t border-white/10">
                <div @click="handleLogout" class="nav-link w-full text-red-100 hover:bg-red-500/10">
                    <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i> Déconnexion
                </div>
            </div>
        </aside>

        <!-- En-tête de page desktop (Persistant & Dynamique) -->
        <div class="bg-white border-b border-gray-100 px-6 py-4 hidden lg:block">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold text-gray-900">{{ currentRouteTitle }}</h1>
                    <p class="text-sm text-gray-500">{{ currentRouteSubtitle }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Cloche de notification dynamique -->
                    <div @click="setRoute('notifications')"
                       class="relative p-2 rounded-xl text-gray-400 hover:bg-gray-100 cursor-pointer transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span v-if="unreadNotifsCount > 0" class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            {{ unreadNotifsCount }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone de Contenu Principale avec transition fluide -->
        <main class="flex-1 p-4 lg:p-6">

            <!-- Flash messages gérés de manière réactive par Vue -->
            <div v-if="flashMessage" class="mb-4 flex items-center gap-3 border px-4 py-3 rounded-xl text-sm transition-all"
                 :class="flashMessage.type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'">
                <i :data-lucide="flashMessage.type === 'success' ? 'check-circle' : 'alert-circle'" class="w-5 h-5 shrink-0" :class="flashMessage.type === 'success' ? 'text-green-500' : 'text-red-500'"></i>
                <span>{{ flashMessage.text }}</span>
                <button @click="flashMessage = null" class="ml-auto hover:bg-black/5 p-0.5 rounded-lg"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>

            <!-- RENDER DES COMPOSANTS DE PAGE (Simulation d'architecture de Layout) -->
            <div class="fade-enter-active">

                <!-- 1. VUE : TABLEAU DE BORD -->
                <div v-if="currentRoute === 'dashboard'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="stat-card flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i data-lucide="award" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Moyenne Générale</p>
                                <p class="text-xl font-bold text-gray-900 mt-0.5">15.8 / 20</p>
                            </div>
                        </div>
                        <div class="stat-card flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i data-lucide="calendar-check" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Taux de Présence</p>
                                <p class="text-xl font-bold text-gray-900 mt-0.5">94.2 %</p>
                            </div>
                        </div>
                        <div class="stat-card flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                                <i data-lucide="files" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Pièces Justificatives</p>
                                <p class="text-xl font-bold text-gray-900 mt-0.5">3 / 4 Validées</p>
                            </div>
                        </div>
                        <div class="stat-card flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                <i data-lucide="megaphone" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Annonces</p>
                                <p class="text-xl font-bold text-gray-900 mt-0.5">{{ unreadAnnoncesCount }} non lues</p>
                            </div>
                        </div>
                    </div>

                    <!-- Grille de raccourcis & Informations récentes -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="card lg:col-span-2 space-y-4">
                            <h3 class="text-base font-bold text-gray-800">Dernières Notes</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Matière</th>
                                            <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Note</th>
                                            <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Semestre</th>
                                            <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        <tr v-for="note in notes.slice(0, 3)" :key="note.id">
                                            <td class="py-3 text-sm font-medium text-gray-700">{{ note.matiere }}</td>
                                            <td class="py-3 text-sm font-bold text-gray-900">{{ note.note }}/20</td>
                                            <td class="py-3 text-sm text-gray-500">{{ note.semestre }}</td>
                                            <td class="py-3">
                                                <span class="badge bg-green-50 text-green-700">Validé</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card space-y-4">
                            <h3 class="text-base font-bold text-gray-800">Cours du jour</h3>
                            <div class="space-y-3">
                                <div v-for="cours in edt.slice(0,2)" :key="cours.id" class="p-3 bg-slate-50 rounded-xl border border-gray-100">
                                    <p class="text-xs text-indigo-600 font-bold">{{ cours.horaire }}</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ cours.matiere }}</p>
                                    <p class="text-xs text-gray-400 flex items-center gap-1 mt-1">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i> {{ cours.salle }} • {{ cours.enseignant }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. VUE : MON INSCRIPTION -->
                <div v-if="currentRoute === 'inscription'" class="card max-w-4xl mx-auto space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-lg font-bold text-gray-800">Dossier Administratif d'Inscription</h2>
                        <p class="text-sm text-gray-500 mt-1">Veuillez vérifier les informations ci-dessous fournies par le service de scolarité.</p>
                    </div>
                    <form @submit.prevent="saveInscription" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom Complet</label>
                                <input v-model="student.nom_complet" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label>
                                <input v-model="student.email" type="email" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <input v-model="student.phone" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Filière d'Étude</label>
                                <select class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                                    <option>Master 1 Génie Logiciel (Groupe 27)</option>
                                    <option>Master 1 Cybersécurité</option>
                                    <option>Licence 3 Administration Réseaux</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" @click="setRoute('dashboard')" class="btn-secondary">Annuler</button>
                            <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>

                <!-- 3. VUE : RE-INSCRIPTION -->
                <div v-if="currentRoute === 'reinscription'" class="max-w-3xl mx-auto space-y-6">
                    <div class="card space-y-4">
                        <div class="flex items-center gap-3 text-amber-600">
                            <i data-lucide="info" class="w-6 h-6"></i>
                            <h2 class="text-base font-bold">Campagne de réinscription {{ currentYear }}/{{ currentYear+1 }}</h2>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Vous êtes actuellement inscrit en Master 1. Pour formaliser votre passage ou réinscription à l'année universitaire supérieure, merci de télécharger les justificatifs requis et de valider ce formulaire.
                        </p>
                    </div>

                    <div class="card space-y-5">
                        <h3 class="text-base font-bold text-gray-800">Étape 1 : Validation administrative</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-gray-100">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Quittance de paiement des frais de scolarité</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Format PDF accepté.</p>
                                </div>
                                <button class="btn-secondary text-xs py-2 px-3">Téléverser</button>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-gray-100">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Attestation de réussite précédente</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Pour vérification de vos crédits ECTS.</p>
                                </div>
                                <span class="badge bg-emerald-100 text-emerald-800">Déjà validé</span>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button @click="triggerSuccessMessage('Votre demande de ré-inscription a été soumise avec succès !')" class="btn-primary">
                                Soumettre mon dossier ré-inscription
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4. VUE : DOCUMENTS -->
                <div v-if="currentRoute === 'documents'" class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-base font-bold text-gray-800">Mes Documents Administratifs</h2>
                        <button @click="simulateFileUpload" class="btn-primary">
                            <i data-lucide="upload" class="w-4 h-4"></i> Téléverser un document
                        </button>
                    </div>
                    <div class="card">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Nom du document</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Type</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Date d'import</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Statut</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr v-for="doc in documents" :key="doc.id">
                                        <td class="py-3 text-sm font-semibold text-gray-800">{{ doc.name }}</td>
                                        <td class="py-3 text-sm text-gray-500 font-mono">{{ doc.type }}</td>
                                        <td class="py-3 text-sm text-gray-500">{{ doc.date }}</td>
                                        <td class="py-3">
                                            <span :class="['badge', doc.status === 'Validé' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800']">
                                                {{ doc.status }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <button @click="downloadSimulatedDoc(doc.name)" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Télécharger</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 5. VUE : MES NOTES -->
                <div v-if="currentRoute === 'notes'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="stat-card">
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Crédits acquis</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">48 / 60 ECTS</p>
                        </div>
                        <div class="stat-card">
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Matières validées</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">8 / 10</p>
                        </div>
                        <div class="stat-card">
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Prochain jury</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">Juillet {{ currentYear }}</p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Unité d'Enseignement (UE)</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Note</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Semestre</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Crédits ECTS</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Résultat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr v-for="note in notes" :key="note.id">
                                        <td class="py-4 text-sm font-semibold text-gray-800">{{ note.matiere }}</td>
                                        <td class="py-4 text-sm font-bold text-gray-900">{{ note.note }}/20</td>
                                        <td class="py-4 text-sm text-gray-500 font-mono">{{ note.semestre }}</td>
                                        <td class="py-4 text-sm text-gray-500">{{ note.ects }}</td>
                                        <td class="py-4">
                                            <span :class="['badge', note.note >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                                                {{ note.note >= 10 ? 'Validé' : 'À repasser' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 6. VUE : EMPLOI DU TEMPS -->
                <div v-if="currentRoute === 'edt'" class="space-y-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                        <p class="text-sm font-medium text-slate-500">Semaine en cours (Master 1 - Groupe 27)</p>
                        <div class="flex gap-2">
                            <button class="btn-secondary text-xs">Semaine précédente</button>
                            <button class="btn-secondary text-xs">Semaine suivante</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div v-for="day in daysOfWeek" :key="day" class="card p-4 space-y-4">
                            <div class="border-b border-slate-100 pb-2">
                                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">{{ day }}</h3>
                            </div>
                            <div class="space-y-3 min-h-[300px]">
                                <div v-for="cours in getCoursesForDay(day)" :key="cours.id"
                                     class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100/50 hover:shadow-md transition-shadow">
                                    <p class="text-[10px] text-indigo-700 font-bold tracking-wide">{{ cours.horaire }}</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-1 leading-snug">{{ cours.matiere }}</p>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ cours.salle }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ cours.enseignant }}</p>
                                </div>
                                <div v-if="getCoursesForDay(day).length === 0" class="text-xs text-slate-400 text-center py-10 italic">
                                    Aucun cours prévu
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. VUE : MES PRÉSENCES -->
                <div v-if="currentRoute === 'presences'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="stat-card flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Heures Validées</p>
                                <p class="text-2xl font-bold text-gray-900 mt-0.5">180 heures</p>
                            </div>
                        </div>
                        <div class="stat-card flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Absences Injustifiées</p>
                                <p class="text-2xl font-bold text-gray-900 mt-0.5">2 heures</p>
                            </div>
                        </div>
                    </div>

                    <div class="card space-y-4">
                        <h3 class="text-base font-bold text-gray-800">Registre d'émargement</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Date</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Matière</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Enseignant</th>
                                        <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Émargement</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr v-for="row in attendanceHistory" :key="row.id">
                                        <td class="py-3.5 text-sm text-gray-600">{{ row.date }}</td>
                                        <td class="py-3.5 text-sm font-semibold text-gray-800">{{ row.matiere }}</td>
                                        <td class="py-3.5 text-sm text-gray-500">{{ row.enseignant }}</td>
                                        <td class="py-3.5">
                                            <span :class="['badge', row.status === 'Présent' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                                                {{ row.status }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 8. VUE : ANNONCES -->
                <div v-if="currentRoute === 'annonces'" class="max-w-4xl mx-auto space-y-6">
                    <div v-for="annonce in annonces" :key="annonce.id" class="card relative overflow-hidden space-y-3" :class="{'border-l-4 border-l-indigo-500 bg-indigo-50/10': !annonce.read}">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="badge bg-indigo-100 text-indigo-800 mb-2">{{ annonce.category }}</span>
                                <h2 class="text-lg font-bold text-gray-800 leading-tight">{{ annonce.title }}</h2>
                            </div>
                            <span class="text-xs text-gray-400">{{ annonce.date }}</span>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ annonce.content }}</p>
                        <div class="flex justify-end pt-2">
                            <button v-if="!annonce.read" @click="markAnnonceAsRead(annonce.id)" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold">
                                Marquer comme lue
                            </button>
                            <span v-else class="text-xs text-gray-400 flex items-center gap-1">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Déjà consultée
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 9. VUE : NOTIFICATIONS -->
                <div v-if="currentRoute === 'notifications'" class="max-w-3xl mx-auto space-y-4">
                    <div class="flex justify-between items-center pb-2">
                        <p class="text-sm text-gray-500 font-medium">{{ unreadNotifsCount }} nouvelles notifications</p>
                        <button v-if="unreadNotifsCount > 0" @click="clearAllNotifs" class="text-xs text-red-500 hover:text-red-700 font-bold">
                            Tout supprimer
                        </button>
                    </div>

                    <div v-for="notif in notifications" :key="notif.id"
                         class="card flex items-start gap-4 p-4 border transition-all"
                         :class="notif.read ? 'bg-white border-slate-100' : 'bg-amber-50/30 border-amber-100'">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                             :class="notif.type === 'grade' ? 'bg-emerald-50 text-emerald-600' : 'bg-indigo-50 text-indigo-600'">
                            <i :data-lucide="notif.type === 'grade' ? 'award' : 'megaphone'" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ notif.title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ notif.message }}</p>
                            <p class="text-[10px] text-gray-400 mt-2">{{ notif.time }}</p>
                        </div>
                        <button v-if="!notif.read" @click="markNotifAsRead(notif.id)" class="text-xs text-indigo-600 hover:text-indigo-800 shrink-0 font-medium">
                            Marquer
                        </button>
                    </div>

                    <div v-if="notifications.length === 0" class="card py-16 text-center text-slate-400 italic">
                        Aucune notification enregistrée
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer Persistant -->
        <footer class="px-6 py-4 border-t border-gray-100 text-xs text-gray-400 text-center bg-white">
            © {{ currentYear }} — Plateforme Universitaire Modernisée en Vue 3
        </footer>
    </div>
</div>

<script>
    // Initialisation et logique applicative Vue 3
    const { createApp, ref, computed, nextTick } = Vue;

    createApp({
        setup() {
            // État utilisateur réactif (anciennement auth()->user() en Blade)
            const student = ref({
                nom_complet: "Alexandre Dupont",
                matricule: "2026-GL04892",
                email: "alexandre.dupont@groupe27.univ.fr",
                phone: "+33 6 12 34 56 78"
            });

            // Année universitaire calculée
            const currentYear = ref(new Date().getFullYear());

            // Routage interne réactif de l'application (Simule un routeur)
            const currentRoute = ref("dashboard");
            const mobileMenuOpen = ref(false);

            // Flash Messages dynamiques
            const flashMessage = ref(null);

            // Éléments de navigation pour la boucle mobile
            const navItems = [
                { route: 'dashboard', icon: 'layout-dashboard', label: 'Tableau de bord' },
                { route: 'inscription', icon: 'clipboard-list', label: 'Mon inscription' },
                { route: 'reinscription', icon: 'refresh-cw', label: 'Ré-inscription' },
                { route: 'documents', icon: 'folder-open', label: 'Mes documents' },
                { route: 'notes', icon: 'file-text', label: 'Mes notes' },
                { route: 'edt', icon: 'calendar', label: 'Emploi du temps' },
                { route: 'presences', icon: 'check-square', label: 'Mes présences' },
                { route: 'annonces', icon: 'megaphone', label: 'Annonces' },
                { route: 'notifications', icon: 'bell', label: 'Notifications' }
            ];

            // Liste des cours de l'Emploi du Temps
            const edt = ref([
                { id: 1, jour: 'Lundi', horaire: '08:30 - 11:30', matiere: 'Architecture Logicielle', salle: 'Amphi B', enseignant: 'Dr. Martin' },
                { id: 2, jour: 'Lundi', horaire: '13:30 - 16:30', matiere: 'Vue.js 3 Avancé & Composants', salle: 'Labo Info 4', enseignant: 'Mme. Durand' },
                { id: 3, jour: 'Mardi', horaire: '10:00 - 13:00', matiere: 'Conception d\'APIs REST', salle: 'Salle 208', enseignant: 'Dr. Martin' },
                { id: 4, jour: 'Mercredi', horaire: '14:00 - 17:00', matiere: 'Bases de données NoSQL', salle: 'Salle 102', enseignant: 'Mr. Bernard' },
                { id: 5, jour: 'Jeudi', horaire: '08:30 - 11:30', matiere: 'Gestion de Projet Agile', salle: 'Salle d\'études 3', enseignant: 'Mme. Robert' },
                { id: 6, jour: 'Vendredi', horaire: '10:00 - 12:00', matiere: 'Anglais Technique', salle: 'Salle 304', enseignant: 'Mr. Smith' }
            ]);

            const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];

            const getCoursesForDay = (day) => {
                return edt.value.filter(course => course.jour === day);
            };

            // Liste des notes (Simulée)
            const notes = ref([
                { id: 1, matiere: 'Architecture Logicielle', note: 16.5, semestre: 'Semestre 1', ects: 6 },
                { id: 2, matiere: 'Vue.js 3 Avancé & Composants', note: 18.0, semestre: 'Semestre 1', ects: 6 },
                { id: 3, matiere: 'Conception d\'APIs REST', note: 14.2, semestre: 'Semestre 1', ects: 4 },
                { id: 4, matiere: 'Bases de données NoSQL', note: 15.0, semestre: 'Semestre 1', ects: 4 },
                { id: 5, matiere: 'Gestion de Projet Agile', note: 12.0, semestre: 'Semestre 2', ects: 4 },
                { id: 6, matiere: 'Anglais Technique', note: 13.5, semestre: 'Semestre 2', ects: 2 },
            ]);

            // Historique des présences
            const attendanceHistory = ref([
                { id: 1, date: '15/05/2026', matiere: 'Vue.js 3 Avancé', enseignant: 'Mme. Durand', status: 'Présent' },
                { id: 2, date: '14/05/2026', matiere: 'Conception d\'APIs REST', enseignant: 'Dr. Martin', status: 'Présent' },
                { id: 3, date: '12/05/2026', matiere: 'Bases de données NoSQL', enseignant: 'Mr. Bernard', status: 'Absent' },
                { id: 4, date: '11/05/2026', matiere: 'Architecture Logicielle', enseignant: 'Dr. Martin', status: 'Présent' }
            ]);

            // Documents simulés
            const documents = ref([
                { id: 1, name: "Carte_Etudiant_2026.pdf", type: "PDF", date: "12/09/2025", status: "Validé" },
                { id: 2, name: "Attestation_Inscription_M1.pdf", type: "PDF", date: "15/09/2025", status: "Validé" },
                { id: 3, name: "Quittance_Scolarite_Acompte.pdf", type: "PDF", date: "20/09/2025", status: "En cours" }
            ]);

            // Annonces administratives réactives
            const annonces = ref([
                { id: 1, category: "Scolarité", date: "Aujourd'hui", title: "Lancement de la campagne de réinscription 2026/2027", content: "Les inscriptions en ligne pour l'année universitaire 2026/2027 sont ouvertes. Veuillez uploader les documents nécessaires via l'onglet Ré-inscription.", read: false },
                { id: 2, category: "Examens", date: "Hier", title: "Planning des soutenances de projet M1", content: "Les soutenances finales pour le module de Génie Logiciel se dérouleront entre le 15 et le 18 Juin 2026. Le planning détaillé par groupe sera publié d'ici peu.", read: false },
                { id: 3, category: "Événement", date: "05 Mai 2026", title: "Hackathon inter-promo de l'Université", content: "Participez au grand Hackathon annuel qui aura lieu le week-end du 23 Mai. Inscription gratuite par équipe de 4 étudiants maximum auprès de l'administration.", read: true }
            ]);

            // Notifications dynamiques
            const notifications = ref([
                { id: 1, type: 'grade', title: 'Nouvelle note disponible', message: 'Votre note pour le projet "Vue.js 3 Avancé" a été publiée : 18.0/20.', time: 'Il y a 2 heures', read: false },
                { id: 2, type: 'info', title: 'Mise à jour Emploi du temps', message: 'Le cours d\'Anglais du vendredi 10h00 est déplacé en salle 304.', time: 'Il y a 1 jour', read: false },
                { id: 3, type: 'info', title: 'Dossier validé', message: 'Votre attestation d\'inscription pour l\'année universitaire en cours a été validée.', time: 'Il y a 3 jours', read: true }
            ]);

            // Propriétés calculées pour les badges dynamiques
            const unreadAnnoncesCount = computed(() => {
                return annonces.value.filter(a => !a.read).length;
            });

            const unreadNotifsCount = computed(() => {
                return notifications.value.filter(n => !n.read).length;
            });

            // Titres et sous-titres dynamiques par route
            const currentRouteTitle = computed(() => {
                switch(currentRoute.value) {
                    case 'dashboard': return "Mon Tableau de Bord";
                    case 'inscription': return "Dossier Administratif";
                    case 'reinscription': return "Campagne de Ré-inscription";
                    case 'documents': return "Mes Fichiers Scolaires";
                    case 'notes': return "Mes Résultats Académiques";
                    case 'edt': return "Mon Emploi du Temps";
                    case 'presences': return "Suivi d'Émargement";
                    case 'annonces': return "Annonces de l'Université";
                    case 'notifications': return "Toutes mes Notifications";
                    default: return "Mon Espace";
                }
            });

            const currentRouteSubtitle = computed(() => {
                switch(currentRoute.value) {
                    case 'dashboard': return "Consultez vos statistiques, cours et actualités en temps réel.";
                    case 'inscription': return "Mettez à jour vos données personnelles et suivez votre statut.";
                    case 'reinscription': return "Procédez à votre réinscription administrative pour le semestre suivant.";
                    case 'documents': return "Téléversez et téléchargez vos documents d'études validés.";
                    case 'notes': return "Consultez vos relevés de notes et crédits ECTS cumulés.";
                    case 'edt': return "Votre agenda hebdomadaire de cours synchronisé.";
                    case 'presences': return "Historique de vos présences en cours et justificatifs validés.";
                    case 'annonces': return "Tenez-vous au courant des événements et alertes administratives.";
                    case 'notifications': return "Historique et gestion de vos alertes scolaires.";
                    default: return "";
                }
            });

            // Méthode de routage simplifiée
            const setRoute = (route) => {
                currentRoute.value = route;
                mobileMenuOpen.value = false;
                // Forcer Lucide à re-générer les icônes des sous-composants injectés
                nextTick(() => {
                    lucide.createIcons();
                });
            };

            // Fonction utilitaire pour extraire les initiales du profil
            const getInitials = (fullName) => {
                if (!fullName) return "E";
                return fullName.split(' ').map(word => word[0]).join('').slice(0, 2).toUpperCase();
            };

            // Actions d'enregistrement et téléversement simulées
            const saveInscription = () => {
                flashMessage.value = {
                    type: 'success',
                    text: 'Vos informations de dossier d\'inscription ont été mises à jour avec succès !'
                };
                setRoute('dashboard');
            };

            const simulateFileUpload = () => {
                const docNames = [
                    "Justificatif_Assurance_2026.pdf",
                    "Certificat_Medical.pdf",
                    "RIB_Rib_Banque.pdf"
                ];
                const randomName = docNames[Math.floor(Math.random() * docNames.length)];

                documents.value.push({
                    id: documents.value.length + 1,
                    name: randomName,
                    type: "PDF",
                    date: "Aujourd'hui",
                    status: "En cours"
                });

                flashMessage.value = {
                    type: 'success',
                    text: `Le fichier "${randomName}" a été téléversé pour étude par la scolarité.`
                };

                nextTick(() => {
                    lucide.createIcons();
                });
            };

            const downloadSimulatedDoc = (docName) => {
                alert(`Simulation du téléchargement sécurisé du fichier : ${docName}`);
            };

            const markAnnonceAsRead = (id) => {
                const idx = annonces.value.findIndex(a => a.id === id);
                if (idx !== -1) {
                    annonces.value[idx].read = true;
                }
            };

            const markNotifAsRead = (id) => {
                const idx = notifications.value.findIndex(n => n.id === id);
                if (idx !== -1) {
                    notifications.value[idx].read = true;
                }
            };

            const clearAllNotifs = () => {
                notifications.value = [];
            };

            const triggerSuccessMessage = (text) => {
                flashMessage.value = {
                    type: 'success',
                    text: text
                };
                setRoute('dashboard');
            };

            const handleLogout = () => {
                if(confirm("Voulez-vous vraiment vous déconnecter de votre espace réactif ?")) {
                    alert("Simulation de déconnexion. Redirection de session...");
                }
            };

            return {
                student,
                currentYear,
                currentRoute,
                mobileMenuOpen,
                flashMessage,
                navItems,
                edt,
                daysOfWeek,
                notes,
                attendanceHistory,
                documents,
                annonces,
                notifications,
                unreadAnnoncesCount,
                unreadNotifsCount,
                currentRouteTitle,
                currentRouteSubtitle,
                setRoute,
                getCoursesForDay,
                getInitials,
                saveInscription,
                simulateFileUpload,
                downloadSimulatedDoc,
                markAnnonceAsRead,
                markNotifAsRead,
                clearAllNotifs,
                triggerSuccessMessage,
                handleLogout
            };
        },
        mounted() {
            // Initialisation globale des icônes Lucide au chargement initial
            lucide.createIcons();
        }
    }).mount('#app');
</script>
</body>
</html>
