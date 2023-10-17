<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Yajra\Acl\Models\Permission;
use Yajra\Acl\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        $roles_permissions = [
            'administrador' => [
                'resources' => [
                    'usuarios',
                    'sinodais',
                    'federacoes',
                    'uphs-locais',
                    'pesquisas',
                    'datatables',
                    'tutoriais'
                ]
            ],
            'diretoria' => [
                'resources' => [
                    'sinodais',
                    'atividades',
                    'minhas-demandas',
                    'tutoriais'
                ],
                'permissions' => [
                    'dashboard.datatables.informacao-federacoes',
                    'dashboard.pesquisas.index',
                    'dashboard.pesquisas.show',
                    'dashboard.datatables.pesquisas.sinodais',
                    'dashboard.datatables.pesquisas.federacoes',
                    'dashboard.datatables.pesquisas.locais',
                    'dashboard.pesquisas.acompanhar',
                    'dashboard.datatables.formularios-entregues',
                    'dashboard.formularios-sinodal.export'
                ]
            ],
            'sinodal' => [
                'resources' => [
                    'federacoes',
                    'formularios-sinodais',
                    'tutoriais',
                    'apps'
                ],
                'permissions' => [
                    'dashboard.sinodais.get-ranking',
                    'dashboard.sinodais.update-info',
                    'dashboard.pesquisas.index',
                    'dashboard.pesquisas.show',
                    'dashboard.pesquisas.responder',
                    'dashboard.datatables.formularios-entregues',
                    'dashboard.formularios-local.export',
                    'dashboard.formularios-federacao.export',
                ]
            ],
            'federacao' => [
                'resources' => [
                    'uphs-locais',
                    'formularios-federacoes',
                    'tutoriais'
                ],
                'permissions' => [
                    'dashboard.federacoes.update-info',
                    'dashboard.pesquisas.index',
                    'dashboard.pesquisas.show',
                    'dashboard.pesquisas.responder',
                    'dashboard.datatables.formularios-entregues',
                    'dashboard.formularios-local.export',
                    'dashboard.formularios-federacao.export',
                ]
            ],
            'local' => [
                'resources' => [
                    'formularios-locais',
                    'tutoriais'
                ],
                'permissions' => [
                    'dashboard.pesquisas.index',
                    'dashboard.pesquisas.show',
                    'dashboard.pesquisas.responder',
                    'dashboard.locais.update-info',
                ]
            ],
            'secretaria_estatistica' => [
                'resources' => [
                    'atividades',
                    'secretaria-estatistica',
                    'minhas-demandas',
                    'avisos'
                ],
                'permissions' => [
                    'dashboard.datatables.estatistica.formularios-sinodais',
                    'dashboard.datatables.estatistica.formularios-locais',
                    'dashboard.datatables.formularios-entregues'
                ]
            ]
        ];
        DB::table('permission_role')->truncate();
        try {

            foreach ($roles_permissions as $role_slug => $permissions_array) {
                $role = Role::where('slug', $role_slug)->first();
                $permissions = Permission::whereIn('resource', $permissions_array['resources'])
                    ->get()
                    ->pluck('id')
                    ->toArray();
                if (isset($permissions_array['permissions'])) {
                    $array_permission = Permission::whereIn('slug', $permissions_array['permissions'])
                        ->get()
                        ->pluck('id')
                        ->toArray();
                    array_push($permissions, ...$array_permission);
                }
                $role->syncPermissions($permissions);
            }
        } catch (\Throwable $th) {
            dd($th->getMessage(), $permissions);
        }
    }
}
