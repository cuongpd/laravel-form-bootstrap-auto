<?php

namespace App\Models\Game;

class GameAnalytics extends \App\Models\BaseModel {

    protected $table = 'game_ga';

    static function getOption(){
    	$list = parent::getOption();
        $list['game_store_id']['type'] = 'select';
        $list['game_store_id']['list'] = self::getListGame();

        return $list;
    }

    static function getListOfStore(){
    	$list = self::getList();
    	foreach ($list as $item) {
            $game_store_info = GameStore::getInfo($item->game_store_id);
    		$item->game = GameInfo::getInfo($game_store_info->game_id)->name;
    		$item->server = GameServer::getInfo($game_store_info->server_id)->name;
    	}

    	return $list;
    }

    static function getListGame() {
    	$list = GameStore::fetchAll(true);
        $data = [0 => '--Chose Game--' ];
        foreach( $list as $item ) {
            $data[$item->id] = GameInfo::getInfo($item->game_id)->name. ' - '. GameServer::getInfo($item->server_id)->name;
        }
        return $data;
    }

    static function getListServer() {
    	$list = GameServer::fetchAll(true);
        $data = [0 => '--Chose Server--' ];
        foreach( $list as $item ) {
            $data[$item->id] = $item->name;
        }
        return $data;
    }

}
