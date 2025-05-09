<?php

define("ORDERED_CUSTOMER_QUERY", " 
    SELECT a.id, c.nome AS cliente, a.data, a.ora
    FROM appuntamenti a
    JOIN clienti c ON a.cliente = c.id
    ORDER BY a.data, a.ora
"); // QUERY CHE RESITUISCE TUTTI GLI UTENTI IN BASE A L ORA DELL'ULTIMA VISITA