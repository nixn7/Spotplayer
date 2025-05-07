<?php
class Playlist
{
    private $con;
    private $id;
    private $name;
    private $owner;
    public function __construct($con, $data)
    {


        if (!is_array($data)) {
            $qery = mysqli_query($con, "SELECT * FROM playlists WHERE id='$data'");
            $data = mysqli_fetch_array($qery);
        }
        $this->con = $con;
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->owner = $data['owner'];
    }


    public function getName()
    {
        return $this->name;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNumberOfSongs()
    {
        $query = mysqli_query($this->con, "SELECT * FROM playlistSongs WHERE playlistId='$this->id'");
        return mysqli_num_rows($query);
    }

    public function getSongIds()
    {
        $query = mysqli_query($this->con, "SELECT songid FROM playlistsongs WHERE playlistid='$this->id' ORDER BY playlistOrder ASC");
        $array = array();
        while ($row = mysqli_fetch_array($query)) {
            array_push($array, $row['songid']);
        }
        return $array;
    }

    public function getCoverPath()
    {
        $query = mysqli_query($this->con, "SELECT coverPhoto FROM playlists WHERE id='$this->id'");
        $result = mysqli_fetch_array($query);
        return $result['coverPhoto'];
    }

    public function getCoverPhoto() {
        $id = $this->getId();
        $query = mysqli_query($this->con, "SELECT coverPhoto FROM playlists WHERE id='$id'");
        $row = mysqli_fetch_array($query);
        
        return $row['coverPhoto'];
    }

    public static function getPlaylistsDropdown($con, $username): string
    {
        $dropdown = "<select class=\"playlist bg-zinc-900 text-gray-200 w-full py-1 px-3 rounded border border-zinc-700 focus:outline-none focus:ring-1 focus:ring-green appearance-none cursor-pointer\">";
        $dropdown .= "<option value=\"\" class=\"bg-zinc-800 text-gray-300\">Add to playlist</option>";

        $query = mysqli_query($con, "SELECT id, name FROM playlists WHERE owner='$username'");
        while ($row = mysqli_fetch_array($query)) {
            $id = $row['id'];
            $name = $row['name'];

            $dropdown .= "<option value='$id' class=\" bg-zinc-800 hover:bg-zinc-700 py-1\">$name</option>";
        }

        $dropdown .= "</select>";

        return "<div class=\"relative\">" . $dropdown . "</div>";
    }

}
?>