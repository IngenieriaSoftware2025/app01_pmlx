
CREATE TABLE productos (
    producto_id INT PRIMARY KEY,
    producto_nombre VARCHAR(255) NOT NULL,
    producto_cantidad INT NOT NULL,
    producto_categoria VARCHAR(50) NOT NULL,
    producto_prioridad VARCHAR(50) NOT NULL,
    producto_comprado INT DEFAULT 0,
    producto_situacion INT DEFAULT 1
);
