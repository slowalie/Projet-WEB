<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';


use App\models\Database;

class OffresModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getnomOffres(): array
    {
        $nom_offres = "SELECT nom_offres FROM Offres";
        return $this->db->query($nom_offres);
    }

    public function gettypeOffres(): array
    {
        $type_offres = "SELECT type_offres FROM Offres";
        return $this->db->query($type_offres);
    }
    public function getdureeOffres(): array
    {
        $duree_offres = "SELECT duree_offres FROM Offres";
        return $this->db->query($duree_offres);
    }
    public function getsalaireOffres(): array
    {
        $salaire_offres = "SELECT salaire_offres FROM Offres";
        return $this->db->query($salaire_offres);
    }
    public function getdescriptionOffres(): array
    {
        $description_offres = "SELECT description_offres FROM Offres";
        return $this->db->query($description_offres);
    }

    public function getmissionOffres(): array
    {
        $missions_offres = "SELECT missions FROM Offres";
        return $this->db->query($missions_offres);
    }

    public function getdbOffres(): array
    {
        $db_offres = "SELECT date_debut FROM Offres";
        return $this->db->query($db_offres);
    }

    public function getnoteOffres(): array
    {
        $note_offres = "SELECT note FROM Offres";
        return $this->db->query($note_offres);
    }

    public function getsecteurOffres(): array
    {
        $secteur_offres = "SELECT secteur_offres FROM Offres";
        return $this->db->query($secteur_offres);
    }

    public function getProfilROffres(): array
    {
        $PR_offres = "SELECT Profil_recherche FROM Offres";
        return $this->db->query($PR_offres);
    }

    public function getidentrepriseOffres(): array
    {
        $identreprise_offres = "SELECT id_entreprise FROM Offres";
        return $this->db->query($identreprise_offres);
    }

    public function getidlocalisationOffres(): array
    {
        $id_localisation_offres = "SELECT id_localisation FROM Offres";
        return $this->db->query($id_localisation_offres);
    }

    public function getidOffres(): array
    {
        $id_offres = "SELECT id_offres FROM Offres";
        return $this->db->query($id_offres);
    }





}