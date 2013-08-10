<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

      <title>CPL</title>
  </head>
  <body>




          <h2>Content Area</h2>
                
                <form action="getcpl.php" method="post">
                    <div id="frmStation">Station/DNIS:</div>
                        <select name="dnis" id="dnis">
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
                    <div id="frmBgnDate">Start Query Date (yyyy-mm-dd): </div><input type="date" name="wkbegin" id="wkbegin" />
                    <div id="frmEndDate">End Query Date (yyyy-mm-dd): </div><input type="date" name="wkend" id="wkend" />
                    <input type="submit" value="Submit" />
                </form>
                
          
<div id="chart_div"></div>

    </body>
</html>