<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db.class.php');

class songs{


public function __construct(){
	$this->db = new db;
}

private function err($message){
	$this->error = true;
	$this->errormsg = $message;
}

public function addEventSong($eventid,$songid){
	$data['eventid'] = $eventid;
	$data['songid'] = $songid;
	$this->db->insert('event_songs',$data);
        if ($this->db->error){
                $this->err($this->db->errormsg);
        }
	return true;
}

public function addSong($song,$artistid,$video=Null){
	$data['song'] = $song;
	$data['artist'] = $artist;
	if ($video != Null){
		$data['video'] = $video;
	}	
	$this->db->insert('songs',$data);
	if ($this->db->error){
		$this->err($this->db->errormsg);
	}
	return true;
}


public function getArtists(){
	$sql = 'SELECT * FROM song_artists';
	$result = $this->db->selectAll($sql);
        if ($this->db->error){
                $this->err($this->db->errormsg);
        }
	return $result;
}

public function getSongsByArtist($artistid){
	$sql = "SELECT song WHERE artistid = '$artistid'";
	$result = $this->db->selectAll($sql);
        if ($this->db->error){
                $this->err($this->db->errormsg);
        }
        return $result;
}

public function getSongsByEvent($eventid){
	$sql = "SELECT songid FROM event_songs WHERE eventid = '$eventid'";
        $result = $this->db->selectAll($sql);
        if ($this->db->error){
                $this->err($this->db->errormsg);
        }
        return $result;

}

public function getSongData($songid){
        $sql = "SELECT a.artist s.artistid s.song FROM song_artists a JOIN songs s ON a.id = s.artistid where s.id = '$songid'";
        $result = $this->db->selectAll($sql);
        if ($this->db->error){
                $this->err($this->db->errormsg);
        }
        return $result;
}


public function searchSongs($query){
        $sql = "SELECT a.artist, s.artistid, s.song FROM song_artists a JOIN songs s ON a.id = s.artistid JOIN song_meta m ON m.song_id = s.id where s.song LIKE '%$query%' or a.artist LIKE '%$query%' or m.attribute LIKE '%$query%'";
        $result = $this->db->selectAll($sql);
        if ($this->db->error){
                $this->err($this->db->errormsg);
        }
        return $result;
}

public function searchSongs($query){
	$sql = "SELECT a.artist s.artistid s.song FROM song_artists a JOIN songs s ON a.id = s.artistid where s.song LIKE '%$query%' or a.artist LIKE '%$query%'";
	$result = $this->db->selectAll($sql);
        if ($this->db->error){
                $this->err($this->db->errormsg);
        }
        return $result;
}

public function updateSongMeta($songid,$meta){
	$this->db->remove('song_meta', "song_id = $songid");
	foreach explode(',',$meta) as $attr{
		$row = { 'song_id' => $songid,
			'attribute' => $attr};
		$this->db->insert('song_meta',$row);
	}
}

}

?>
