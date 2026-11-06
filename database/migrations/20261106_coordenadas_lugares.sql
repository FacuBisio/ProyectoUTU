-- Ejecutar una sola vez sobre una base existente y hacer antes una copia de seguridad.
-- Los puntos se contrastaron con OpenStreetMap/Nominatim el 2026-11-06.
-- OSM data: © OpenStreetMap contributors, ODbL.
-- Los lugares sin coordenadas verificables quedan sin marcador para evitar ubicaciones inventadas.
START TRANSACTION;

UPDATE lugar
SET
  LATITUD = CASE ID_LUGAR
    WHEN 9 THEN -31.3980456
    WHEN 10 THEN -31.3799857
    WHEN 11 THEN -31.3734493
    WHEN 15 THEN -31.3868228
    WHEN 16 THEN -31.3887715
    WHEN 17 THEN -31.3872378
    WHEN 21 THEN -31.3857947
    WHEN 23 THEN -31.3740982
    WHEN 26 THEN -31.4565808
    WHEN 33 THEN -31.3874993
    WHEN 34 THEN -31.3841692
    ELSE NULL
  END,
  LONGITUD = CASE ID_LUGAR
    WHEN 9 THEN -57.9624471
    WHEN 10 THEN -57.9515427
    WHEN 11 THEN -57.9781842
    WHEN 15 THEN -57.9682536
    WHEN 16 THEN -57.9592628
    WHEN 17 THEN -57.9661186
    WHEN 21 THEN -57.9690985
    WHEN 23 THEN -57.9589849
    WHEN 26 THEN -57.9112481
    WHEN 33 THEN -57.9666945
    WHEN 34 THEN -57.9439594
    ELSE NULL
  END
WHERE
  (ID_LUGAR = 9 AND NOMBRE = 'Parque Harriague')
  OR (ID_LUGAR = 10 AND NOMBRE = 'Parque Solari')
  OR (ID_LUGAR = 11 AND NOMBRE LIKE 'Parque Ind%Vaimaca Pir%')
  OR (ID_LUGAR = 12 AND NOMBRE = 'Costanera Norte')
  OR (ID_LUGAR = 13 AND NOMBRE = 'Cuevas de San Antonio')
  OR (ID_LUGAR = 14 AND NOMBRE = 'Plaza Roosevelt')
  OR (ID_LUGAR = 15 AND NOMBRE = 'Teatro Larrañaga')
  OR (ID_LUGAR = 16 AND NOMBRE = 'Catedral Basílica San Juan Bautista')
  OR (ID_LUGAR = 17 AND NOMBRE = 'Gran Hotel Concordia')
  OR (ID_LUGAR = 21 AND NOMBRE = 'Museo del Hombre y la Tecnología')
  OR (ID_LUGAR = 22 AND NOMBRE = 'Museo Bellas Artes')
  OR (ID_LUGAR = 23 AND NOMBRE = 'Museo de Horacio Quiroga')
  OR (ID_LUGAR = 24 AND NOMBRE = 'Termas del Daymán')
  OR (ID_LUGAR = 25 AND NOMBRE = 'Termas del Arapey')
  OR (ID_LUGAR = 26 AND NOMBRE = 'Acuamanía')
  OR (ID_LUGAR = 27 AND NOMBRE = 'Porco Negro')
  OR (ID_LUGAR = 28 AND NOMBRE = 'La Ferne')
  OR (ID_LUGAR = 29 AND NOMBRE = 'Cine Sarandí')
  OR (ID_LUGAR = 30 AND NOMBRE = 'El Rancho')
  OR (ID_LUGAR = 31 AND NOMBRE = 'La Trattoria')
  OR (ID_LUGAR = 32 AND NOMBRE = 'La Caldera')
  OR (ID_LUGAR = 33 AND NOMBRE = 'Restaurant Trouville')
  OR (ID_LUGAR = 34 AND NOMBRE = 'Burger King')
  OR (ID_LUGAR = 35 AND NOMBRE = 'Subway')
  OR (ID_LUGAR = 36 AND NOMBRE = 'La Nevada')
  OR (ID_LUGAR = 37 AND NOMBRE = 'Heladería Premium')
  OR (ID_LUGAR = 38 AND NOMBRE LIKE 'Heladería Alfredito%')
  OR (ID_LUGAR = 42 AND NOMBRE = 'La Don Diego')
  OR (ID_LUGAR = 43 AND NOMBRE = 'La Minerva')
  OR (ID_LUGAR = 44 AND NOMBRE = 'La Aurora')
  OR (ID_LUGAR = 45 AND NOMBRE = 'Jefris')
  OR (ID_LUGAR = 46 AND NOMBRE = 'La Recova')
  OR (ID_LUGAR = 47 AND NOMBRE = 'Basalto');

COMMIT;
