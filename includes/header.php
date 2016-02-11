<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Lloguer de vehicles</a>
    </div>
    <div>
      <ul class="nav navbar-nav">

	<li class="dropdown">
	    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Lloguers <b class="caret"></b></a>
	    <ul class="dropdown-menu">
		 <li><a href="lloga_vehicle.php">Llogar vehicle</a></li> 
		 <li><a href="llista_lloguer.php">Llista de lloguers en curs</a></li> 
	    </ul>
	</li>
	<li class="dropdown">
	    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Vehicles <b class="caret"></b></a>
	    <ul class="dropdown-menu">
        	<li><a href="afegir_vehicle.php">Alta vehicle</a></li>
       		<li <?php if($page == "llista") echo "class='active'"?>><a href="index.php">Llista dels vehicles</a></li> 
	    </ul>
	</li>
	<li><a href="dades_revisio.php">Revisions</a></li> 
	<li><a href="logout.php">Desconectar</a></li> 
      </ul>
    </div>
  </div>
</nav>


