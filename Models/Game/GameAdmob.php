<?php

namespace App\Models\Game;

class GameAdmob extends \App\Models\BaseModel {

    protected $table = 'game_admob';

    static function getOption(){
    	$list = parent::getOption();
        $list['game_store_id']['type'] = 'select';
        $list['game_store_id']['list'] = self::getListGame();

        $list['type_ad']['type'] = 'select';
        $list['type_ad']['list'] = ['POPUP','BANNER'];

        return $list;
    }

    static function getListOfStore(){
    	$list = self::getList();
    	foreach ($list as $item) {
            $game_store_info = GameStore::getInfo($item->game_store_id);
    		$item->game = GameInfo::getInfo($game_store_info->game_id)->name;
    		$item->server = GameServer::getInfo($game_store_info->server_id)->name;
            $item->type = $item->type_ad == 1 ? 'BANNER' : 'POPUP';
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

}
