<nav id="topbar" style="min-width:880px;background-image:url('images/topbar.jpg');box-shadow: 0 2px 8px #000;">
    <ul>
		<li><a href="profil.php">Zdravo, <?php echo $user; ?>!<img src="images/settings.png" style="margin:3px 0 -3px 10px" height="16" width="16" /></a>
			<ul class="sub2">
				<li><a href="profil.php">Profil</a></li>
			</ul>
		</li>
	</ul>
	<ul style="float:right">
		<li><a href="index.php">Početna</a>
		</li>
		<li><a href="prodajap.php">Prodaja</a>
			<ul>
				<li><a href="prodajau.php">Unos internet prodaje</a></li>
				<li><a href="veleprodajau.php">Unos veleprodaje</a></li>
				<li><a href="prodajamu.php">Unos pazara</a></li>
				<li><a href="bfposrednau.php">Biofresh posredna</a></li>
				<li><a href="bfpresek.php">Biofresh presek</a></li>
			</ul>
		</li>
		<li><a href="nabavkap.php">Nabavka</a>
			<ul>
				<li><a href="nabavkaus.php">Jednostavna nabavka</a></li>
				<li><a href="nabavkau.php">Detaljna nabavka</a></li>
				<li><a href="nabavkap.php">Pregled</a></li>
			</ul>
		</li>
		<li><a href="zalihe.php">Skladište</a>
			<ul>
				<li><a href="zalihe.php">Stanje zaliha</a></li>
				<li><a href="konsignacija.php">Konsignacija</a></li>
				<li><a href="konsignacija2.php" title="За Префърцунените">Konsignacija 2</a></li>
				<li><a href="medjusklad.php">Međuskladišnice</a></li>
				<li><a href="popis.php">Popis robe</a></li>
			</ul>
		</li>
		<li><a href="#">Unos i izmena</a>
			<ul>
				<li><a href="proizvodi.php">Proizvodi</a></li>
				<li><a href="gproizvoda.php">Grupe proizvoda</a></li>
				<li><a href="brendovi.php">Brendovi</a></li>
				<li><a href="skladista.php">Skladišta</a></li>
				<li><a href="partneri.php">Partneri</a></li>
				<li><a href="gpartnera.php">Grupe partnera</a></li>
				<li><a href="ctarife.php">Carinske tarife i stope</a></li>
				<li><a href="kurs.php">Kurs</a></li>
			</ul>
		</li>
		<li><a href="#">Izveštaji</a>
			<ul>
				<li><a href="profaktura.php">Profaktura</a></li>
				<li><a href="faktura.php">Faktura</a></li>
				<li><a href="profaktura2.php" title="За префърцунените">Profaktura 2</a></li>
				<li><a href="faktura2.php" title="За префърцунените">Faktura 2</a></li>
				<li><a href="profaktura3.php" title="За най-префърцунените">Profaktura 3</a></li>
				<li><a href="faktura3.php" title="За най-префърцунените">Faktura 3</a></li>
				<li><a href="fakturavp.php">Faktura za veleprodaju</a></li>
				<li><a href="izvmsklad.php">Izv. međuskladišnica</a></li>
				<li><a href="izvestajkase.php">Izveštaj kase</a></li>
				<li><a href="prodajap.php">Hronološki pregled</a></li>
				<li><a href="hrongrafikoni.php">Hronološki grafikoni</a></li>
				<li><a href="prodajap2.php">Pregled po partnerima</a></li>
				<li><a href="poproizvodima.php">Po proizvodima</a></li>
				<li><a href="razlikapopisa.php">Razlika u popisu robe</a></li>
			</ul>
		</li>
<?php if ($level>3) echo '<li><a href="adminpanel.php">Admin panel</a></li>'; ?>
		<li><a href="logout.php">Odjava</a></li>
	</ul>
</nav>