-- Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
-- Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
-- Copyright (C) 2004-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
-- Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
-- Copyright (C) 2004      Guillaume Delecourt  <guillaume.delecourt@opensides.be>
-- Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
-- Copyright (C) 2007 	   Patrick Raguin       <patrick.raguin@gmail.com>
-- Copyright (C) 2010-2011 Herve Prot           <herve.prot@symeos.com>
--
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program. If not, see <http://www.gnu.org/licenses/>.
--
--

--
-- Ne pas placer de commentaire en fin de ligne, ce fichier est parsé lors
-- de l'install et tous les sigles '--' sont supprimés.
--

--
-- Types action comm
--

delete from llx_c_actioncomm where id in (1,2,3,4,5,8,9,10,30,31,50);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 1,  'AC_TEL',     '2', 'Phone call'							,NULL, 2);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 2,  'AC_FAX',     '2', 'Send Fax'							,NULL, 3);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 3,  'AC_PROP',    '2', 'Send commercial proposal by email'	,'propal',  10);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 4,  'AC_EMAIL',   '2', 'Send Email'							,NULL, 4);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 5,  'RDV_RDV',     '1', 'Rendez-vous'							,NULL, 1);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 8,  'AC_COM',     '0', 'Send customer order by email'		,'order',   8);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 9,  'AC_FAC',     '0', 'Send customer invoice by email'		,'invoice', 6);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 10, 'AC_SHIP',    '0', 'Send shipping by email'				,'shipping', 11);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 30, 'AC_SUP_ORD', '0', 'Send supplier order by email'		,'order_supplier',    9);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values  (31, 'AC_SUP_INV', '0', 'Send supplier invoice by email'		,'invoice_supplier', 7);
insert into llx_c_actioncomm (id, code, type, libelle, module, position) values ( 50, 'AC_OTH',     '2', 'Other'								,NULL, 5);

insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (11, 'AC_LEAD', '0', 'Lead change', 'lead',1 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (12, 'AC_PROSPECT', '0', 'Prospect change', 'agenda',1 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (20, 'AC_PRDV', '2', 'Prendre rendez-vous', '',15 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (21, 'AC_CRR', '2', 'Compte-rendu', '',4 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (22, 'AC_DOC', '2', 'Envoye documentation', '',6 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (23, 'RDV_TELC', '1', 'Conference Telephonique', '',12 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (24, 'RDV_WEB', '1', 'Web conference', '',10 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (25, 'AC_NEWSUS', '0', 'Nouveau suspect', '',6 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (26, 'AC_QUALIF', '0', 'Qualification', '',12 , 1);
insert into llx_c_actioncomm (id,code,type,libelle,module,priority,active) VALUES (27, 'AC_SUSP', '0', 'Jamais contacté -> prospect', '',10 , 1);

update llx_c_actioncomm set active=0,priority=1 where code='AC_COM';
update llx_c_actioncomm set priority=1 where code='AC_EMAIL';
update llx_c_actioncomm set active=0,priority=1 where code='AC_FAC';
update llx_c_actioncomm set active=0,priority=1 where code='AC_LEAD';
update llx_c_actioncomm set active=0,priority=0 where code='AC_OTH';
update llx_c_actioncomm set active=0,priority=1 where code='AC_PROP';
update llx_c_actioncomm set active=0,priority=1 where code='AC_SUP_INV';
update llx_c_actioncomm set active=0,priority=1 where code='AC_SUP_ORD';
update llx_c_actioncomm set priority=5 where code='AC_FAX';
update llx_c_actioncomm set priority=13 where code='AC_TEL';
update llx_c_actioncomm set priority=11 where code='RDV_RDV';
update llx_c_actioncomm set active=0,priority=1 where code='AC_PROSPECT';
