<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo $_GET['qs'][0].'/'.$_GET['qs'][1]; ?>/lecon/<?php echo $_GET['qs'][3]; ?>/quiz/<?php echo $_GET['qs'][5]; ?>/question/nouvelle" data-onsubmit="validateNewQuestion">
	<h1>Nouvelle question</h1>
	<div class="clr"></div>
	<textarea placeholder="Question" name="description" id="description"></textarea>

	<p>Cette question vaut <span id="valeurQuestion">0</span> point(s). <span id="noCheckbox">L'étudiant pourra cocher une seule réponse. Son pointage sera celui de la question qu'il a coché.</span><span id="yesCheckbox" style="display:none;">L'étudiant pourra cocher plusieurs réponses. Son pointage sera celui des réponses cochées.</span></p>

	<div class="reponse">
		<input type="text" class="text reponse" placeholder="Réponse" name="r1" id="r1"/>
		<select class="points" name="r1v" id="r1v" onchange="updateQ();">
			<option value="-5">-5 points</option>
			<option value="-4">-4 points</option>
			<option value="-3">-3 points</option>
			<option value="-2">-2 points</option>
			<option value="-1">-1 point</option>
			<option value="0" selected="selected">&mdash;</option>
			<option value="1">1 point</option>
			<option value="2">2 points</option>
			<option value="3">3 points</option>
			<option value="4">4 points</option>
			<option value="5">5 points</option>
			<option value="6">6 points</option>
			<option value="7">7 points</option>
			<option value="8">8 points</option>
			<option value="9">9 points</option>
			<option value="10">10 points</option>
		</select>
	</div>
	<div class="reponse">
		<input type="text" class="text reponse" placeholder="Réponse" name="r2" id="r2"/>
		<select class="points" name="r2v" id="r2v" onchange="updateQ();">
			<option value="-5">-5 points</option>
			<option value="-4">-4 points</option>
			<option value="-3">-3 points</option>
			<option value="-2">-2 points</option>
			<option value="-1">-1 point</option>
			<option value="0" selected="selected">&mdash;</option>
			<option value="1">1 point</option>
			<option value="2">2 points</option>
			<option value="3">3 points</option>
			<option value="4">4 points</option>
			<option value="5">5 points</option>
			<option value="6">6 points</option>
			<option value="7">7 points</option>
			<option value="8">8 points</option>
			<option value="9">9 points</option>
			<option value="10">10 points</option>
		</select>	</div>
	<div class="reponse">
		<input type="text" class="text reponse" placeholder="Réponse" name="r3" id="r3"/>
		<select class="points" name="r3v" id="r3v" onchange="updateQ();">
			<option value="-5">-5 points</option>
			<option value="-4">-4 points</option>
			<option value="-3">-3 points</option>
			<option value="-2">-2 points</option>
			<option value="-1">-1 point</option>
			<option value="0" selected="selected">&mdash;</option>
			<option value="1">1 point</option>
			<option value="2">2 points</option>
			<option value="3">3 points</option>
			<option value="4">4 points</option>
			<option value="5">5 points</option>
			<option value="6">6 points</option>
			<option value="7">7 points</option>
			<option value="8">8 points</option>
			<option value="9">9 points</option>
			<option value="10">10 points</option>
		</select>	</div>
	<div class="reponse">
		<input type="text" class="text reponse" placeholder="Réponse" name="r4" id="r4"/>
		<select class="points" name="r4v" id="r4v" onchange="updateQ();">
			<option value="-5">-5 points</option>
			<option value="-4">-4 points</option>
			<option value="-3">-3 points</option>
			<option value="-2">-2 points</option>
			<option value="-1">-1 point</option>
			<option value="0" selected="selected">&mdash;</option>
			<option value="1">1 point</option>
			<option value="2">2 points</option>
			<option value="3">3 points</option>
			<option value="4">4 points</option>
			<option value="5">5 points</option>
			<option value="6">6 points</option>
			<option value="7">7 points</option>
			<option value="8">8 points</option>
			<option value="9">9 points</option>
			<option value="10">10 points</option>
		</select>	</div>
	<input type="hidden" id="nbRep" name="nbRep" value="4"/>

	<div class="clr"></div>
	<a href="javascript:newQuestion();" onclick="return newQuestion();">Ajouter une réponse</a>

	<label for="melangerreponse" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;margin:5px 0;">
		<input type="checkbox" name="melangerreponse" id="melangerreponse" checked="checked"/>
		Mélanger les réponses
	</label>

	<label for="checkbox" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;margin:5px 0;">
		<input type="checkbox" name="checkbox" id="checkbox" onchange="updateQ();"/>
		Permettre de sélectionner plusieurs réponses
	</label>

	<a id="fichierQuestion" href="javascript:allowFichier();" onclick="return allowFichier();">Joindre une image à la question (optionel)</a>

	<label id="fichierContainer" for="fichier" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:none;">
		<input type="file" class="text" name="fichier" id="fichier"/>
	</label>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
</form>