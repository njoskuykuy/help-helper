<?php
function GetIdVideo($link_video){
  preg_match("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $link_video, $matches);
  $id_url_video = $matches[0];
  return $id_url_video;
}




function ConvertStatus($status){
    if($status == 'Y'){
      return '<label class="label label-success">Aktif</label>';
    }else{
      return '<label class="label label-default">Nonaktif</label>';
    }
  }


function ConvertLevel($level){
    if($level == 0){
      return '<label class="label label-success">Super Administrator</label>';
    }else{
      return '<label class="label label-warning">Administrator</label>';
    }
  }

  
function StrUpper($string){
  // fungsi untuk Capitalkan semua string
  return addslashes(strtoupper($string));
}

function StrLower($string){
  return addslashes(strtolower($string));
}

function StrTitle($string){
  // fungsi untuk 
  return addslashes(ucwords($string));
}

function StrNum($string){
  // fungsi untuk Capitalkan semua string
  return addslashes($string);
}

function NoSpace($string){
  return str_replace(" ", "", $string);
}


function clearing($text){
  $test = str_replace("<text>", "", $text);
  $test = str_replace("</text>", "", $test);
  $test = str_replace("</Translation>", "", $test);
  $test = str_replace('<Translation code="200" lang="id-en">', "", $test);
  $test = str_replace('<Translation code="200" lang="en-id">', "", $test);
  $test = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>", "", $test);
  return $test;

}

function getpage() {
   $pageURL = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
   $pageURL .= "://";
   if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
   } else {
    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
   }
   return $pageURL;
}



function GetExtensi($var){
  $eks = strtolower(substr(strrchr($var, "."),1));
  return $eks;
}


// fungsi enkripsi base64 dengan key
function enkripsiID($plain_text, $password, $iv_len = 4)
{
    $plain_text .= "\x13";
    $n = strlen($plain_text);
    if ($n % 4) $plain_text .= str_repeat("\0", 4 - ($n % 4));
        $i = 0;
        $enc_text = get_rnd_iv($iv_len);
        $iv = substr($password ^ $enc_text, 0, 512);
    while ($i < $n) {
        $block = substr($plain_text, $i, 4) ^ pack('H*', md5($iv));
        $enc_text .= $block;
        $iv = substr($block . $iv, 0, 512) ^ $password;
        $i += 4;
    }
    $hasil=base64_encode($enc_text);
    return str_replace('+', '@', $hasil);
}
 
// untuk mendekripsi string base64
function dekripsiID($enc_text, $password, $iv_len = 4)
{
    $enc_text = str_replace('@', '+', $enc_text);
    $enc_text = base64_decode($enc_text);
    $n = strlen($enc_text);
    $i = $iv_len;
    $plain_text = '';
    $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
    while ($i < $n) {
    $block = substr($enc_text, $i, 4);
    $plain_text .= $block ^ pack('H*', md5($iv));
    $iv = substr($block . $iv, 0, 512) ^ $password;
    $i += 4;
    }
    return preg_replace('/\\x13\\x00*$/', '', $plain_text);
}
 
function get_rnd_iv($iv_len)
{
    $iv = '';
    while ($iv_len-- > 0) {
    $iv .= chr(mt_rand() & 0xff);
    }
    return $iv;
}

function getfeeling(){
  $datefungsi = str_replace("-", "",date("Y-m-d"));
  return $nice =  $datefungsi.'Kmzwa87awaa';
}


function minify($buffer)
{
    $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
    $replace = array(
        '>',
        '<',
        '\\1'
        );
  $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

function EXPORT_TABLES($host,$user,$pass,$name,$tables=false, $backup_name=false){ 
    set_time_limit(3000); $mysqli = new mysqli($host,$user,$pass,$name); $mysqli->select_db($name); $mysqli->query("SET NAMES 'utf8'");
    $queryTables = $mysqli->query('SHOW TABLES'); while($row = $queryTables->fetch_row()) { $target_tables[] = $row[0]; }   if($tables !== false) { $target_tables = array_intersect( $target_tables, $tables); } 
    $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";
    foreach($target_tables as $table){
        if (empty($table)){ continue; } 
        $result = $mysqli->query('SELECT * FROM `'.$table.'`');     $fields_amount=$result->field_count;  $rows_num=$mysqli->affected_rows;     $res = $mysqli->query('SHOW CREATE TABLE '.$table); $TableMLine=$res->fetch_row(); 
        $content .= "\n\n".$TableMLine[1].";\n\n";
        for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
            while($row = $result->fetch_row())  { //when started (and every after 100 command cycle):
                if ($st_counter%100 == 0 || $st_counter == 0 )  {$content .= "\nINSERT INTO ".$table." VALUES";}
                    $content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  else{$content .= '""';}     if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
                //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";} $st_counter=$st_counter+1;
            }
        } $content .="\n\n\n";
    }
    $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
    $backup_name = $backup_name ? $backup_name : $name."-".date('H-i-s')."_".date('d-m-Y')."-".rand(1,11111111).".sql";
    ob_get_clean(); header('Content-Type: application/octet-stream');   header("Content-Transfer-Encoding: Binary"); header("Content-disposition: attachment; filename=\"".$backup_name."\"");
    echo $content; exit;
} 
  function terbilang($x, $style=4) {
      if($x<0) {
          $hasil = "minus ". trim(kekata($x));
      } else {
          $hasil = trim(kekata($x));
      }     
      switch ($style) {
          case 1:
              $hasil = strtoupper($hasil.' rupiah');
              break;
          case 2:
              $hasil = strtolower($hasil.' rupiah');
              break;
          case 3:
              $hasil = ucwords($hasil.' rupiah');
              break;
          default:
              $hasil = ucfirst($hasil.' rupiah');
              break;
      }     
      return $hasil;
}

function kekata($x) {
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x <12) {
        $temp = " ". $angka[$x];
    } else if ($x <20) {
        $temp = kekata($x - 10). " belas";
    } else if ($x <100) {
        $temp = kekata($x/10)." puluh". kekata($x % 10);
    } else if ($x <200) {
        $temp = " seratus" . kekata($x - 100);
    } else if ($x <1000) {
        $temp = kekata($x/100) . " ratus" . kekata($x % 100);
    } else if ($x <2000) {
        $temp = " seribu" . kekata($x - 1000);
    } else if ($x <1000000) {
        $temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
    } else if ($x <1000000000) {
        $temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
    } else if ($x <1000000000000) {
        $temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
    }     
        return $temp;
}

function CekSuperAdmin(){
  $superAdmin = 1;
  return $superAdmin;
}


function anti_injeksi($data){
  $filter  = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
  return $filter;
}

function CekPinjaman($bataspinjam){
  //jika hasil 1 maka dapat dinyatakan peminjaman telat.
  $hari =  date("Y-m-d");
  // $hari =  "2018-01-10";
  $today = strtotime($hari);
  $bataspinjam = strtotime($bataspinjam);
  if($bataspinjam >= $today){
    $cek = 0;
  }else{
    $cek = 1;
  }
  return $cek;
}

function SelisihPinjam($tglpinjam){
  $tglpinjam = new DateTime("$tglpinjam");
  $today = new DateTime();
  // $today =  new DateTime("2018-01-10");
  $interval = $today->diff($tglpinjam);
  if($interval->d != 0){
    $plus = $interval->d;
  }else{
    $plus = $interval->d;
  }
  return $plus;
}

function enkripsi($enk){
  $res = md5(md5($enk));
  return $res;
}

function BuatKode(){
  $dbhost = "localhost";
  $dbuser = "root";
  $dbpass = "";
  $dbname = "perpustakaan_if";
  $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if(!$conn){
    echo mysqli_error();
  }

  $sqlSetting = mysqli_query($conn, "SELECT * FROM settings WHERE id = 1");
  if(mysqli_num_rows($sqlSetting) > 0){
    $getSetting =  $sqlSetting->fetch_assoc();
  }

  $sql_kode = mysqli_query($conn, "SELECT RIGHT(nomor_transaksi,5) AS kode FROM peminjaman ORDER BY id DESC LIMIT 1");
  $hit_kod = mysqli_num_rows($sql_kode);
  if($hit_kod <> 0){
    $data_kod = mysqli_fetch_assoc($sql_kode);
    $kode = $data_kod['kode'] + 1;
  }else{
    $kode = 1;
  }
  $m_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
  $kodefix = "$getSetting[kode_prefix]-$m_kode";
  return $kodefix;
}

function BatasPinjam($tglpinjam, $periode){
  // $periode = '+7 days';
  $hasil = date('Y-m-d', strtotime($periode, strtotime($tglpinjam)));
  return $hasil;
}

function Berhasil($pesan){
  $_SESSION['NotifBerhasil'] = $pesan;
}

function Gagal($pesan){
  $_SESSION['NotifGagal'] = $pesan;
}

function GagalVer2(){
  $pesan = 'Maaf, terjadi kesalahan silahkan coba lagi.';
  $_SESSION['NotifGagal'] = $pesan;
}

function bulan($bln){
$bulan = $bln;
Switch ($bulan){
 case 1 : $bulan="Januari";
 Break;
 case 2 : $bulan="Februari";
 Break;
 case 3 : $bulan="Maret";
 Break;
 case 4 : $bulan="April";
 Break;
 case 5 : $bulan="Mei";
 Break;
 case 6 : $bulan="Juni";
 Break;
 case 7 : $bulan="Juli";
 Break;
 case 8 : $bulan="Agustus";
 Break;
 case 9 : $bulan="September";
 Break;
 case 10 : $bulan="Oktober";
 Break;
 case 11 : $bulan="November";
 Break;
 case 12 : $bulan="Desember";
 Break;
 }
return $bulan;
}

//fungsi seo
function seo($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');

    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d

    $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $s;
}
//fungsi tanggal
function tgl_indo($tgl){
  $tanggal = substr($tgl,8,2);
  $bulan   = getBulan(substr($tgl,5,2));
  $tahun   = substr($tgl,0,4);
  return $tanggal.' '.$bulan.' '.$tahun;
}
function getBulan($bln){
  switch ($bln){
    case 1:
    return "Januari";
    break;
    case 2:
    return "Februari";
    break;
    case 3:
    return "Maret";
    break;
    case 4:
    return "April";
    break;
    case 5:
    return "Mei";
    break;
    case 6:
    return "Juni";
    break;
    case 7:
    return "Juli";
    break;
    case 8:
    return "Agustus";
    break;
    case 9:
    return "September";
    break;
    case 10:
    return "Oktober";
    break;
    case 11:
    return "November";
    break;
    case 12:
    return "Desember";
    break;
  }
}

function generate_pass($panjang)
{
    $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
    $string = '';
    for ($i = 0; $i < $panjang; $i++) {
  $pos = rand(0, strlen($karakter)-1);
  $string .= $karakter{$pos};
    }
    return $string;
}

 ?>
