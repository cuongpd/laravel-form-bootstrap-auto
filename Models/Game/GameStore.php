<?php

namespace App\Models\Game;

class GameStore extends \App\Models\BaseModel {

    protected $table = 'game_store';

    static function getOption(){
    	$list = parent::getOption();
        $list['game_id']['type'] = 'select';
        $list['game_id']['list'] = self::getListGame();

        $list['server_id']['type'] = 'select';
        $list['server_id']['list'] = self::getListServer();

        return $list;
    }

    static function getListOfStore(){
    	$list = self::fetchAll();
    	foreach ($list as $item) {
    		$item->game = GameInfo::getInfo($item->game_id)->name;
    		$item->server = GameServer::getInfo($item->server_id)->name;
    	}

    	return $list;
    }

    static function getListGame() {
    	$list = GameInfo::fetchAll(true);
        $data = [0 => '--Chose Game--' ];
        foreach( $list as $item ) {
            $data[$item->id] = $item->name;
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
