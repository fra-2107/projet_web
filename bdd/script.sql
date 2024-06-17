#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: fk_arb_etat
#------------------------------------------------------------

CREATE TABLE fk_arb_etat(
        fk_arb_etat Varchar (50) NOT NULL
,CONSTRAINT fk_arb_etat_PK PRIMARY KEY (fk_arb_etat)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: fk_stadedev
#------------------------------------------------------------

CREATE TABLE fk_stadedev(
        fk_stadedev Varchar (50) NOT NULL
,CONSTRAINT fk_stadedev_PK PRIMARY KEY (fk_stadedev)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: fk_port
#------------------------------------------------------------

CREATE TABLE fk_port(
        fk_port Varchar (50) NOT NULL
,CONSTRAINT fk_port_PK PRIMARY KEY (fk_port)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: fk_pied
#------------------------------------------------------------

CREATE TABLE fk_pied(
        fk_pied Varchar (50) NOT NULL
,CONSTRAINT fk_pied_PK PRIMARY KEY (fk_pied)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: arbre
#------------------------------------------------------------

CREATE TABLE arbre(
        id          Int  Auto_increment  NOT NULL ,
        haut_tot    Float NOT NULL ,
        haut_tronc  Float NOT NULL ,
        diam_tronc  Float NOT NULL ,
        lat         Float NOT NULL ,
        longi       Float NOT NULL ,
        fk_arb_etat Varchar (50) ,
        fk_stadedev Varchar (50) ,
        fk_pied     Varchar (50) ,
        fk_port     Varchar (50)
,CONSTRAINT arbre_PK PRIMARY KEY (id)

,CONSTRAINT arbre_fk_arb_etat_FK FOREIGN KEY (fk_arb_etat) REFERENCES fk_arb_etat(fk_arb_etat)
,CONSTRAINT arbre_fk_stadedev0_FK FOREIGN KEY (fk_stadedev) REFERENCES fk_stadedev(fk_stadedev)
,CONSTRAINT arbre_fk_pied1_FK FOREIGN KEY (fk_pied) REFERENCES fk_pied(fk_pied)
,CONSTRAINT arbre_fk_port2_FK FOREIGN KEY (fk_port) REFERENCES fk_port(fk_port)
)ENGINE=InnoDB;