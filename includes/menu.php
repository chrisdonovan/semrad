<!--- Menu Bar --->
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="brand" href="/"><strong>KDE Semrad Metrics</strong></a>
      <div class="nav-collapse collapse">	
        <ul class="nav mynav-tabs"><?php
        $pages = array('home','upload','cpl');

        foreach($pages as $page){
        if($page == 'home'){
        echo '<li><a href="/">' . ucwords($page) . '</a></li>';
        }
        else{
        echo '<li><a href="/' . $page . '.php">' . ucwords($page) . '</a></li>';
        }
        }
        ?></ul>
      </div>
    </div>
  </div>
</div>
<!--- End Menu Bar --->
