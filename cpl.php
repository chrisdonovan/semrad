<?php require_once("includes/functions.php"); ?>
<?php define('TITLE','CPL'); ?>
<?php include("includes/header.php"); ?>
<?php include("includes/menu.php"); ?>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2 hidden-phone"></div>
    <div class="span8 center">
      <h2>Content Area</h2>

      <form action="getcpl.php" method="post">
        <div id="frmStation">Station/DNIS:</div>
        <select name="dnis" id="dnis" class="input-xxlarge" style="margin: 0 0 25px 0;">
          <option value="28">8200 - Radio, Atlanta - WAMJ</option>
          <option value="29">2840 - Radio, Atlanta - WPZE</option>
          <option value="126">2848 - Radio, Atlanta - WALR</option>
          <option value="128">2846 - Radio, Atlanta - Radio One Remnant</option>
          <option value="52">8954 - Radio, Atlanta - Cox Remnant</option>
          <option value="6">2588 - TV, Atlanta - WUPA</option>
          <option value="7">8966 - TV, Atlanta - WPCH</option>
          <option value="8">2580 - TV, Atlanta - WAGA</option>
          <option value="37">4537 - Radio, Nashville - WQQK</option>
          <option value="127">4541 - Radio, Nashville - WQQK Remnant</option>
          <option value="9">4540 - TV, Nashville - WZTV</option>
          <option value="10">4543 - TV, Nashville - WUXP</option>
          <option value="35">1446 - Radio, Atlanta - Barrington</option>
          <option value="36">4542 - Radio, Nashville - Barrington</option>
          <option value="00">Atlanta - Overall CPL</option>
          <option value="01">Nashville - Overall CPL</option>
        </select>
        <div class="clear"></div>

        <div class="calendars">
          <div style="float:left">
          <input id="wkbegin" type="text" name="wkbegin" placeholder="Begin Query Date" />
          <div id="bgncal" style="margin: 0 0 315px 0"></div>
          </div>

          <div style="float:right">
          <input id="wkend" type="text" name="wkend" placeholder="End Query Date" />
          <div id="endcal" style="margin: 0 0 300px 0"></div>
          </div>
          <div class="clear"></div>
        </div>

        <div class="clear">
          <input type="submit" value="Submit" class="btn btn-primary btn-large" />
        </div>
      </form>

      

    </div>
  </div>
</div>