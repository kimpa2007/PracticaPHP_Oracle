# PracticaPHP_Oracle
Pràctica en Php que estableix conexió al SGBD Oracle

Aquesta pràctica es basa en una bases de dades de una empresa de lloguer de vehicles.

<b>Plantejament inicial</b>

A la base de dades hi ha guardats vehicles, venedors i clients d’un servei de lloguer de cotxes que
disposa de diverses delegacions. Quan un client lloga un vehicle, s’afegeix una fila a la taula
LLOGUER amb valors a les columnes codi, codi client, codi vehicle, codi venedor, data inicial i km
inicials, mentre que les columnes data final, km finals i retorn queden amb un valor NULL. Quan el
client retorna el vehicle, s’omplen aquestes dades amb els valors corresponents. A la columna
retorn cal posar-hi A, B, C o D en funció de l’estat de retorn (A: Impecable, B: Necessita neteja, C:
Portar al taller i D: Sinistre).

<b>Apartat comú</b>
Escriviu el codi HTML i PHP necessari per a gestionar, des d’un entorn web, algunes de les
tasques habituals del procés de lloguer de vehicles:

1. Una primera pantalla web ha de demanar a l’usuari que introdueixi el seu codi d’usuari i
la seva contrasenya d'Oracle. Un cop introduïts, es passarà a una nova pàgina on hi haurà
les següents opcions:
        a) Donar d’alta un vehicle (demanarà totes les dades d’un vehicle nou per afegir-lo
        a la BD, es pot limitar a models existents).
        b) Llogar un vehicle (demanarà les dades necessàries per fer un lloguer, segons el
        que s’ha explicat al plantejament inicial d’aquesta pràctica). A més, en fer un
        lloguer, cal poder llegar accessoris juntament amb el vehicle. Cal assegurar-se que
        els accessoris existeixen i són compatibles amb el model de vehicle.
        c) Consultar els vehicles de l’empresa (sense demanar cap dada mostrarà el llistat
        de tots els vehicles de l’empresa i, en cas que estiguin llogats, la data d’inici del
        lloguer).
        d) Retornar un vehicle llogat (demanarà les dades necessàries per acabar un
        lloguer, segons el que s’ha explicat al plantejament de la pràctica i el que toqui
        segons el darrer apartat particular de cadascú).
        e) Aquesta darrera opció dependrà de l’opció que cal fer de la pràctica (A o B)
        2. Cal disposar de totes les opcions indicades i implementades en diversos fitxers
        gestionant adequadament el pas de paràmetres entre les planes (com ara el nom d’usuari i
        el password — no es poden demanar a cada plana). No cal fer un tractament exhaustiu
        d’errors ni fer una interfície molt sofisticada, però es valorarà la simplicitat de cara a
        l’usuari.
Detall: encara que seria normal entrar les dates a partir del valor actual del sistema (“avui”), com
que es tracta d’un exercici acadèmic cal poder entrar les dates manualment per testejar el
funcionament de les opcions.

<b> Revisions</b>

Quan un client retorna el vehicle que ha llogat (apartat d) cal comprovar si el vehicle s’ha de portar
a passar la revisió mecànica. 

La política de l'empresa és que els vehicles passin la revisió, aproximadament, cada 5.000 km pel cas de gasolina, 7.500 pels dièsel i 10.000 pels elèctrics. Cal disposar d’una taula REVISIONS on hi haurà un històric amb totes les revisions que ha fet cada vehicle (cal guardar, com a mínim, el codi de vehicle, la data, els km del vehicle quan es va fer la
revisió i el codi del venedor encarregat de portar el cotxe a revisió). 

Cada vegada que es retorna un vehicle, es comprova si li toca o no passar la revisió i, si s'ha de fer, afegeix una fila a la taula de revisions amb el codi del vehicle, la data d’avui, els km que tenia quan es va retornar i el codi de venedor que porta el cotxe a revisió. A més, si l’estat de retorn del vehicle és ‘C’, cal portar el vehicle a revisió independentment dels quilòmetres que porti. 

El venedor que porta el cotxe a revisió és el més jove de la delegació on s’ha fet el lloguer. Si un vehicle no ha tingut mai cap revisió cal revisar-lo després del primer lloguer.

Cal que la mateixa opció de retornar un vehicle insereixi automàticament, si cal, un registre a la taula de revisions. Com a precondició tenim que a la taula de lloguers ja existeix una fila pel cotxe que es retorna i les columnes dataf , kmf, i retorn a NULL. I com a postcondició tenim que si li tocava passar revisió, s'haurà afegit una nova fila a la taula de revisions. Pel que fa a la opció e) del menú particular d’aquest enunciat, caldrà demanar un codi de vehicle i mostrar totes les revisions que ha tingut. També cal mostrar si es troba o no llogat i els km que falten fins a la propera revisió.

<b>Cal destacar que els objectes en PHP són molts millorables, podrien ser més senzill amb herència. 
El objecte de Oci (que interactua amb la base de dades) es també millorable (afegint-li per fer les consultes, per com es retorni resultat etc), però de cares a la pràctica demanada es més que suficient </b>
