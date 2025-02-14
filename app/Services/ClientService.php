<?php

namespace App\Services;
use App\helpers\UtilHelper;
use App\Models\Client;
use App\Models\ClientInfo;
use Auth;

class ClientService
{

    public function queryAll( $params = [])
    {

    }

    public function getAll($language = null)
    {


    }

    public function store($request)
    {
      $client = new Client();
      $client->user_id = $request['user_id'];
      $client->title_general = $request['name'];
      $client->country_id = $request['country_id'];
      $client->contacts = $request['contacts'];
      $client->email = $request['email'];
      $client->phone = $request['phone'];
      $client->commerce_no = $request['commerce_no'];
      // $client->logo = $request['logo'];
      // $client->banner = $request['banner'];
      if (isset($request['parent_id'])) {$client->parent_id = $request['parent_id'];}
      if (isset($request['template_id'])) {$client->template_id = $request['template_id'];}
      if (isset($request['notify_mail'])) {$client->notify_mail = $request['notify_mail'];}
      $client->mobile = $request['mobile'];
      $client->administrator = $request['administrator'];
      $client->ip = UtilHelper::getUserIp();
      $client->access_user_id = $request['user_id'];
      $client->save();

      if (!$client) {
        return false;
      }

      return $client;

    }

    public function storeInfo($request)
    {
      $clientInfo = new ClientInfo();
      $clientInfo->client_id = $request['client_id'];
      $clientInfo->language = $request['language'];
      $clientInfo->title = $request['name'];
      $clientInfo->alias = $request['alias'];
      $clientInfo->address = $request['address'];
      $clientInfo->description = $request['description'];
      $clientInfo->meta_tags = $request['meta_tags'];
      $clientInfo->meta_keywords = $request['meta_keywords'];
      // $client->logo = $request['logo'];
      // $client->banner = $request['banner'];
      $clientInfo->meta_description = $request['meta_description'];
      $clientInfo->work_times = $request['work_times'];
      $clientInfo->ip = UtilHelper::getUserIp();
      $clientInfo->access_user_id = $request['user_id'];
      $clientInfo->save();

      if (!$clientInfo) {
        return false;
      }

      return $clientInfo;

    }

    public function update($request,$client)
    {

      // $client->title_general = $request['name'];
      $client->country_id = $request['country_id'];
      $client->contacts = $request['contacts'];
      $client->email = $request['email'];
      $client->phone = $request['phone'];
      $client->commerce_no = $request['commerce_no'];
      // $client->logo = $request['logo'];
      // $client->banner = $request['banner'];
      if (isset($request['parent_id'])) {$client->parent_id = $request['parent_id'];}
      if (isset($request['template_id'])) {$client->template_id = $request['template_id'];}
      if (isset($request['notify_mail'])) {$client->notify_mail = $request['notify_mail'];}
      $client->mobile = $request['mobile'];
      $client->administrator = $request['administrator'];
      $client->ip = UtilHelper::getUserIp();
      $client->access_user_id = Auth::id();
      $client->save();

      if (!$client) {
        return false;
      }


      $client->save();

      return $client;

    }

    public function updateInfo($request,$clientInfo)
    {

      $clientInfo->title = $request['name'];
      $clientInfo->alias = $request['alias'];
      $clientInfo->address = $request['address'];
      $clientInfo->description = $request['description'];
      $clientInfo->meta_tags = $request['meta_tags'];
      $clientInfo->meta_keywords = $request['meta_keywords'];
      // $client->logo = $request['logo'];
      // $client->banner = $request['banner'];
      $clientInfo->meta_description = $request['meta_description'];
      $clientInfo->work_times = $request['work_times'];
      $clientInfo->ip = UtilHelper::getUserIp();
      $clientInfo->access_user_id = Auth::id();
      $clientInfo->save();

      if (!$clientInfo) {
        return false;
      }

      return $clientInfo;

    }

}
