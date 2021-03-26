<link rel="stylesheet" href="css/person.css" />
<link rel="stylesheet" href="css/comment.css" />
<?php

session_start();

include 'database.php';

$pageMainURL = "http://barto.ct8.pl/strona/";

$ID = getVariable( "id" );
$referer = isset( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ] : "";

if ( $referer != "$pageMainURL.?a=admin&password=w6EUPz4e6FyXLkAA" ) {
  $qry = $db->prepare( "SELECT * FROM book WHERE ID = :ID AND (display = 1)" );
  $qry->bindParam( ":ID", $ID );
  $qry->execute();
} else {
  $qry = $db->prepare( "SELECT * FROM book WHERE ID = :ID" );
  $qry->bindParam( ":ID", $ID );
  $qry->execute();
}

if ( $qry->rowCount() > 0 ) {
  $person = $qry->fetch();

  $name = getVariable( "name" );
  $text = getVariable( "text" );

  if ( $name != "" && $text != "" ) {
    $qry = $db->prepare( "INSERT INTO comments (ID, book_id, name, text, ip, time) VALUES (NULL, :ID, :name, :text, :ip, UNIX_TIMESTAMP())" );
    $qry->bindParam( ":ID", $ID );
    $qry->bindParam( ":name", $name );
    $qry->bindParam( ":text", $text );
    $qry->execute();

    header( "Location: " . $pageMainURL . "?a=person&id=" . $ID );
    exit();
  }

  $qry = $db->prepare( "SELECT * FROM comments WHERE book_id = :ID AND (display = 1)" );
  $qry->bindParam( ":ID", $ID );
  $qry->execute();

  $comments = $qry->fetchAll();
  ?>

<div class="row">
  <div class="col-sm-12 aktualnosciNaglowek border-bottom border-warning">
    <h1>
      <?=$person["name"]?>
      <?=$person["lastname"]?>
    </h1>
  </div>
</div>
<div class="row">
	

	<div class="col-md-4 col-sm-12"></div>
	<div class="col-md-4 col-sm-12 ">
		<div class="w-100 h-100 picture">
	   <img id="profilepic" src="<?=$pageMainURL?>.<?=$person["avatar_url"]?>" class="img-fluid rounded-circle shadow-lg p-1 mb-3 mt-3 bg-warning rounded picture picture_size">
	  	<div id="resurrect-div" class="overlay ctr rounded-circle"><button type="button" id="resurrect" class="btn picture text-light w-100 h-100 ozyw_przycisk noselect"><h1>Ożyw zmarłego</h1></button></div>
		</div>
	  
	  </div>
	<div class="col-md-4 col-sm-12"></div>





</div>

			
<div class="row">
  <div class="text-light text-justify mt-3">
    <?=$person["description"]?>
  </div>
</div>
<?php
if ( $person[ "grave_url" ] != "" ) {
  ?>
<br />
<div class="row">
  <div class="grave_url col-sm-12 aktualnosciNaglowek border-warning">
    <h1><a href="<?=$pageMainURL?>.?a=walk&id=<?=$person["ID"]?>" class="facebookKontakt text-light menuOpcja">Link do spaceru</a></h1>
  </div>
</div>
<?php
}
?>
<div class="row">
  <div class="col-sm-12 aktualnosciNaglowek border-warning border-top mt-5">

  </div>
</div>
<!--<div class="row">
  <div class="mx-auto text-center w-100 p-3">
    <h3> Napisz swój komentarz </h3>
    <form action="/?a=person&id=<?=$person["ID"]?>" method="post" >
      <input class="form-control-lg bg-dark text-light color-warning formularz mb-3 mt-3 border-warning" type="text" name="name" placeholder="Twoje imię i nazwisko" />
      <textarea class="form-control bg-dark text-light text-left color-warning formularz mb-3 mt-3 border-warning mw-80" type="text" name="text" placeholder="Komentarz" rows="5"></textarea>
      <input type="submit" value="Napisz komentarz" class="btn btn-dark border-warning btn-lg formularz mb-3 mt-5" />
    </form>
  </div>
</div>
</div> -->

<section>
    <div class="container">
        <div class="row">
			
			            <div class="col-lg-4 col-md-5 col-sm-4 offset-md-1 offset-sm-1 col-12 mt-4">
                <form id="algin-form">
                    <div class="form-group">
                        <h4>Zostaw komentarz!</h4> <label for="message">Wiadomość</label> <textarea name="msg" id="" msg cols="30" rows="15" class="form-control"></textarea>
                    </div>
           
                    <div class="form-group">
                        <p class="text-secondary">Tutaj coś trzeba napisać  <a href="#" class="alert-link">bo pasuje</a>, you know, pasuje.</p>
                    </div>
                    <div class="form-inline"> <input type="checkbox" name="check" id="checkbx" class="mr-1"> <label for="subscribe">Zapoznałem się z regulaminem, yoł</label> </div>
                    <div class="form-group"> <button type="button" id="post" class="btn">Wstaw komentarz</button> </div>
                </form>
            </div>
            <div class="col-sm-5 col-md-6 col-12 pb-4">
                <h1>Komentarze</h1>
                <div class="comment mt-4 text-justify float-left"> <img src="https://i.imgur.com/xuBgdeS.jpeg" alt="" class="rounded-circle" width="40" height="40">
                    <h4>Trele morele</h4> <span>- 13 Sierpień 1337</span> <br>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus numquam assumenda hic aliquam vero sequi velit molestias doloremque molestiae dicta?</p>
                </div>
                <div class="text-justify darker mt-4 float-right"> <img src="https://i.imgur.com/eMflsIu.jpeg" alt="" class="rounded-circle" width="40" height="40">
                    <h4>Bart Simpson</h4> <span>- 30 Luty 0</span> <br>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus numquam assumenda hic aliquam vero sequi velit molestias doloremque molestiae dicta?</p>
                </div>
                <div class="comment mt-4 text-justify"> <img src="https://i.imgur.com/86qpp0x.png" alt="" class="rounded-circle" width="40" height="40">
                    <h4>Kartosz Borytko</h4> <span>- Nawet nie wiem kiedy</span> <br>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus numquam assumenda hic aliquam vero sequi velit molestias doloremque molestiae dicta?</p>
                </div>
                <div class="darker mt-4 text-justify"> <img src="https://i.imgur.com/NNZsPMV.jpg" alt="" class="rounded-circle" width="40" height="40">
                    <h4>Kamotek</h4> <span>- Jutro</span> <br>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus numquam assumenda hic aliquam vero sequi velit molestias doloremque molestiae dicta?</p>
                </div>
            </div>

        </div>
    </div>
</section>




<?php
} else {
  echo "<h3>Wpis o podanym ID nie istnieje</h3>";
}
?>
<div class="row">
  <div class="comments mx-auto border-warning border-bottom pb-5">
    <?php
    foreach ( $comments as $comment ) {
      echo
        '<div class="comment">
			<div class="top">
				<div class="text-center"><h3>' . $comment[ "name" ] . '</h3></div>
				<div class="date text-center mb-5">' . date( "d.m.Y H:i:s", $comment[ "time" ] ) . '</div>
			</div>
			<div class="text-center">' . $comment[ "text" ] . '</div>
		</div>';
    }
    ?>
  </div>
</div>
