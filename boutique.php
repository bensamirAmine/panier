<?php

function createPanier(){

	if (!isset($_SESSION['panier'])) {
		$_SESSION['panier']=array();
		$_SESSION['libelleProduit']=array();
		$_SESSION['qteProduit']=array();
		$_SESSION['prixProduit']=array();
		$_SESSION['verrou']=false;
		$select=$db->query("SELECT tva from products");
		$tva=$select->Fetch(PDO::FETCH_OBJ);
		$_SESSION['panier']['tva']=$tva;
	}
	return true;
}

function ajouterArticle($libelleProduit,$qteProduit,$prixProduit){
	if (createPanier()&& isVerrou()) {
		$position_produit = array_search($libelleProduit,$_SESSION['panier']['libelleProduit']);
		if ($position_produit !== false) {
			$_SESSION['panier']['libelleProduit'][$position_produit]+=$qteProduit;
		}else{
			array_push($_SESSION['panier']['libelleProduit'],$libelleProduit);
			array_push($_SESSION['panier']['qteProduit'],$qteProduit);
			array_push($_SESSION['panier']['prixProduit'],$prixProduit);
		}
	}else{
		echo 'Erreur';
	}
}

function modifierQteProduit($libelleProduit,$qteProduit){
	if (createPanier() && !isVerrou()) {
		if ($qteProduit>0) {
			$position_produit=array_search($_SESSION['panier']['libelleProduit'], $libelleProduit);
			if ($position_produit!==false) {
				$_SESSION['panier']['libelleProduit'][$position_produit]=$qteProduit;
			}
			
		}else{
			supprimerProduit($libelleProduit);
		}
	}else{
		echo "error modifqte";
	}
}


function supprimerArticle()
{
	if (createPanier() && !isVerrou()){
		$tmp=array();
		$tmp['libelleProduit']=array();
		$tmp['qteProduit']=array();
		$tmp['prixProduit']=array();
		$tmp['verrou']=array();
		for ($i=0; $i<count($_SESSION['panier']['libelleProduit']) ; $i++) { 
			if ($_SESSION['panier']['libelleProduit'][$i]!==$libelleProduit) {
			array_push($_SESSION['panier']['libelleProduit'],$_SESSION['panier']['libelleProduit'][$i]);
			array_push($_SESSION['panier']['qteProduit'],$_SESSION['panier']['qteProduit'][$i]);
			array_push($_SESSION['panier']['prixProduit'],$_SESSION['panier']['prixProduit'][$i]);
			}
		}
		$_SESSION['panier']=$tmp;
		unset($tmp);
	}else{
		echo "erreur SA"
	}
}

function montantGlobal(){
	$tot=0;
	for ($i=0; $i<count($_SESSION['panier']['libelleProduit']) ; $i++) {
		$tot+=$_SESSION['panier']['qteProduit'][$i]*$_SESSION['panier']['prixProduit'][$i];
	}
	return $tot;
}

function supprimerPanier(){
	if(isset($_SESSION['panier'])){
		unset($_SESSION['panier']);
	}
}

function isVerrou(){
	if (isset($_SESSION['panier'] && $_SESSION['isVerrou'])) {
		return true;
	}else{
		return false;
	}
}

function compterArticle(){

	if (isset($_SESSION['panier'])) {
		return count($_SESSION['panier']['libelleProduit']);
	}

}

?>