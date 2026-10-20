# Modelo Entidad-Relación y Modelo Relacional

Este modelo documenta la base de datos de SIGTUR. El esquema ejecutable y los datos de ejemplo se encuentran en [`../proyectoutu.sql`](../proyectoutu.sql).

## Modelo Entidad-Relación

```mermaid
erDiagram
    ROL ||--o{ USUARIO : asigna
    USUARIO ||--o{ COMENTARIO : escribe
    USUARIO ||--o{ USUARIO_TELEFONO : registra
    USUARIO ||--o{ EVENTO : publica
    LUGAR o|--o{ EVENTO : aloja
    LUGAR ||--o{ LUGAR_CATEGORIA : clasifica
    CATEGORIA ||--o{ LUGAR_CATEGORIA : agrupa
    USUARIO ||--o{ EVENTO_REACCION : reacciona
    EVENTO ||--o{ EVENTO_REACCION : recibe
    USUARIO ||--o{ EVENTO_COMENTARIO : comenta
    EVENTO ||--o{ EVENTO_COMENTARIO : recibe

    ROL {
        int ID_ROL PK
        varchar NOMBRE
    }
    USUARIO {
        int ID_USUARIO PK
        int ID_ROL FK
        varchar NOMBRE
        varchar CONTRASENA
        varchar CORREO UK
    }
    USUARIO_TELEFONO {
        int ID_USUARIO PK,FK
        varchar TELEFONO PK
    }
    COMENTARIO {
        int ID_COMENTARIO PK
        int ID_USUARIO FK
        text COMENTARIO
        datetime FECHA
    }
    LUGAR {
        int ID_LUGAR PK
        varchar NOMBRE
        text DESCRIPCION
        varchar DIRECCION
        varchar IMAGEN
        decimal LATITUD
        decimal LONGITUD
    }
    CATEGORIA {
        int ID_CATEGORIA PK
        varchar NOMBRE
    }
    LUGAR_CATEGORIA {
        int ID_LUGAR PK,FK
        int ID_CATEGORIA PK,FK
    }
    EVENTO {
        int ID_EVENTO PK
        int ID_USUARIO FK
        int ID_LUGAR FK
        varchar NOMBRE
        text DESCRIPCION
        int DIA
        int MES
        smallint ANIO
        time HORA_INI
        time HORA_FIN
        varchar DIRECCION
        decimal LATITUD
        decimal LONGITUD
        varchar IMAGEN
    }
    EVENTO_REACCION {
        int ID_EVENTO PK,FK
        int ID_USUARIO PK,FK
        tinyint TIPO
        datetime FECHA
    }
    EVENTO_COMENTARIO {
        int ID_COMENTARIO PK
        int ID_EVENTO FK
        int ID_USUARIO FK
        varchar COMENTARIO
        datetime FECHA
    }
```

`LUGAR` y `CATEGORIA` tienen una relación muchos a muchos, resuelta por `LUGAR_CATEGORIA`. `EVENTO.ID_LUGAR` admite valores nulos: un evento puede no estar asociado a un lugar del catálogo.

## Modelo relacional

- **ROL**(**ID_ROL**, NOMBRE)
- **USUARIO**(**ID_USUARIO**, ID_ROL → ROL.ID_ROL, NOMBRE, CONTRASENA, CORREO [único])
- **USUARIO_TELEFONO**(**ID_USUARIO** → USUARIO.ID_USUARIO, **TELEFONO**)
- **COMENTARIO**(**ID_COMENTARIO**, ID_USUARIO → USUARIO.ID_USUARIO, COMENTARIO, FECHA)
- **LUGAR**(**ID_LUGAR**, NOMBRE, DESCRIPCION, DIRECCION, IMAGEN, LATITUD, LONGITUD)
- **CATEGORIA**(**ID_CATEGORIA**, NOMBRE)
- **LUGAR_CATEGORIA**(**ID_LUGAR** → LUGAR.ID_LUGAR, **ID_CATEGORIA** → CATEGORIA.ID_CATEGORIA)
- **EVENTO**(**ID_EVENTO**, ID_USUARIO → USUARIO.ID_USUARIO, ID_LUGAR → LUGAR.ID_LUGAR [opcional], NOMBRE, DESCRIPCION, HORA_INI, HORA_FIN, DIA, MES, ANIO, DIRECCION, LATITUD, LONGITUD, IMAGEN)
- **EVENTO_REACCION**(**ID_EVENTO** → EVENTO.ID_EVENTO, **ID_USUARIO** → USUARIO.ID_USUARIO, TIPO, FECHA)
- **EVENTO_COMENTARIO**(**ID_COMENTARIO**, ID_EVENTO → EVENTO.ID_EVENTO, ID_USUARIO → USUARIO.ID_USUARIO, COMENTARIO, FECHA)

Las claves compuestas se indican con todos sus atributos subrayados en negrita. La base también restringe el tipo de reacción a `-1` o `1` y valida que las coordenadas estén dentro de sus rangos y se almacenen en pareja.
