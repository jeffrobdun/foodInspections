<head>
	<script src="http://code.jquery.com/jquery-2.1.4.min.js" ></script>
	<link href="css/main.css" rel="stylesheet">
	<title>Home</title>
</head>
<body>
<span>Current</span><input type="radio" name="resultMode" value="current" id="current" style="display:none" checked="true"/><img for="current" class="radio" src="https://cdn1.iconfinder.com/data/icons/material-core/20/radio-button-on-24.png" />
<span>Historical</span><input type="radio" name="resultMode" value="historical" id="historical" style="display:none" /><img for="historical" class="radio" src="https://cdn1.iconfinder.com/data/icons/material-core/20/radio-button-off-24.png" />
<br />
<br />
<span style="display:block">Search establishments by name, address, city and/or status</span>
<br/>
<!--<span style="display:block">Leave fields empty to get all results for that field</span>-->
<span id="test"></span>
<div class="top">
	
	<form id="form">
		<span>Name</span><input type="text" name="name" id="name" /><br/>
		<span>Address</span><input type="text" name="address" id="address" /><br/>
		<span>City</span><input type="text" name="city" id="city" /><br/>
		<span>Green</span><input type="checkbox" name="green" id="green" value="green"/><img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/26/unchecked_checkbox.png" class="checkbox" for="green"/><br/>
		<span>Light Yellow</span><input type="checkbox" name="lightYellow" id="lightYellow" value="lightYellow"/><img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/26/unchecked_checkbox.png" class="checkbox" for="lightYellow"/> <br/>
		<span>Dark Yellow</span><input type="checkbox" name="darkYellow" id="darkYellow" value="darkYellow"/><img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/26/unchecked_checkbox.png" class="checkbox" for="darkYellow" /><br/>
		<span>Striped Red</span><input type="checkbox" name="stripedRed" id="stripedRed" value="stripedRed"/><img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/26/unchecked_checkbox.png" class="checkbox" for="stripedRed" /><br/>
		<span>Red</span><input type="checkbox" name="red" id="red" value="red"/><img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/26/unchecked_checkbox.png" class="checkbox" for="red"/><br/>
		<button id="getResturants" name="getResturants" >Get Establishments</button>
	</form>
	
	<div id="legend">
		<span id="legendGreen"><img src="http://www1.gnb.ca/0601/images/public_green.gif" />GREEN: High standard of compliance with no more than 5 minor violations.</span>
		<span id="legendLightYellow"><img src="http://www1.gnb.ca/0601/images/public_yellow_low.gif" />LIGHT YELLOW: General compliance with 6 to 9 minor violations.</span>
		<span id="legendDarkYellow"><img src="http://www1.gnb.ca/0601/images/public_yellow_high.gif" />DARK YELLOW: General compliance with no more than 3 major violations.</span>
		<span id="legendStripedRed"><img src="http://www1.gnb.ca/0601/images/public_red_low.gif" />STRIPED RED: Corrections are required with 1 or more critical violation and/or 4 or more major violations and /or 10 or more minor violations noted.</span>
		<span id="legendRed"><img src="http://www1.gnb.ca/0601/images/public_red_high.gif" />RED: License has been revoked for non-compliance.</span>
	</div>
</div>
<br>
<span>Or search establishments by letter:</span>
<select id="letter">
	<?php
	$letters[0] = '0-9';
	$letters = array_merge($letters, range('A','Z'));
	foreach($letters as $letter){
		print '<option value="' . $letter . '">' . $letter . '</option>';
	} ?>
</select>

<br/>
<br/>
<span id="loading" style="display:none;" /></span>

<table id="results" style="display:none">
	<thead>
		<tr>
			<th data-column="name">Name</th>
			<th data-column="address">Address</th>
			<th data-column="city">City</th>
			<th data-column="colourImage">Inspection Colour</th>
			<th data-column="pdfPath">PDF</th>
			<th data-column="inspectionDate">Inspection Date</th>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
</table>

<div id="templates" style="display:none"></div>
<script src="js/resturants.js" ></script>
<script src="templates/templates.js" ></script>

</div>
</body>