<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;

class AdministrationController extends Controller
{
    public function index()
    {
        $items = Shop::select('id','shop_name','area_id','manager_id')
                        ->with(['area', 'manager'])
                        ->get();
        $managersData = User::where('role',2)->select('id','name','email')->with(['shops:id,shop_name,manager_id'])->get();
        $users = User::where('role',1)->select('id','name','email')->with(['likes'])->get();

        foreach($items as $shop) {
            $shopData = [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name,
                'area' => $shop->area->area,
                'manager_id' => $shop->manager_id,
                'manager_name' => $shop->manager->name,
                'manager_email' => $shop->manager->email,
            ];
            $shops[] = $shopData;
        };
        foreach($managersData as $manager) {
            $managerData = [
                'id' => $manager->id,
                'name' => $manager->name,
                'email' => $manager->email,
                'shops' => $manager->shops ? $manager->shops : null,
            ];
            $managers[] = $managerData;
        };
        return response()->json([
            'shops' => $shops,
            'managers' => $managers,
            'users' => $users,
        ], 200);
    }
}
