<x-app-layout>
    <style>
        /* Estilos generales */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            color: #4a5568;
            margin-bottom: 20px;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Tarjetas */
        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1;
            max-width: 400px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
            font-size: 1.25rem;
            font-weight: bold;
            color: #2d3748;
        }

        .card-body {
            padding: 16px;
        }

        .card-body h6 {
            margin: 0 0 10px;
            font-size: 1rem;
            color: #4a5568;
        }

        .card-body p {
            margin: 0 0 10px;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #3182ce;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: bold;
            text-align: center;
        }

        .btn:hover {
            background-color: #2b6cb0;
        }

        /* Lista de actividades */
        .activity-list {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item span {
            font-size: 0.875rem;
            color: #4a5568;
        }

        .activity-time {
            font-size: 0.75rem;
            color: #718096;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <h1 class="title">Bienvenido, {{ $user->name }}</h1>

        <div class="dashboard flex flex-col md:flex-row gap-6 justify-center">
            <!-- Tarjeta de Información del Usuario -->
            <div class="card user-info">
                <div class="card-header">
                    <h5>Información del Usuario</h5>
                </div>
                <div class="card-body text-center">
                    <h6>Nombre: <strong>{{ $user->name }}</strong></h6>
                    <p>Correo: <strong>{{ $user->email }}</strong></p><br>
                    <a href="{{ route('profile.edit') }}" class="btn">Editar Perfil</a>
                </div>
            </div>

            <!-- Tarjeta de Actividades Recientes -->
            <div class="card activities">
                <div class="card-header">
                    <h5>Actividades Recientes</h5>
                </div>
                <div class="card-body">
                    @if(empty($recentActivities))
                        <p class="text-center">No hay actividades recientes.</p>
                    @else
                        <ul class="activity-list">
                            @foreach($recentActivities as $activity)
                                <li class="activity-item">
                                    <span>{{ $activity->description }}</span>
                                    <span
                                        class="activity-time">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>