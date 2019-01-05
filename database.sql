/*IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = N'DBNAME')
    BEGIN
        CREATE DATABASE projkanban;
    END;
*/
    CREATE TABLE IF NOT EXISTS projkanban.collum(
        cd_collum INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nm_collum VARCHAR(50) NOT NULL,
        cd_order_collum INT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS projkanban.task(
        cd_task INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nm_task VARCHAR(50) NOT NULL,
        cd_order_task INT NOT NULL,
        cd_collum INT NOT NULL,
        cd_type INT NOT NULL,
        cd_responsable INT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS projkanban.type(
        cd_type INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nm_type VARCHAR(50) NOT NULL
    );
    CREATE TABLE IF NOT EXISTS projkanban.responsable(
        cd_responsable INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nm_responsable VARCHAR(50) NOT NULL
    );

    /*ALTER TABLE projkanban.task ADD CONSTRAINT taskCdCollum FOREIGN KEY (cd_collum) REFERENCES collum(cd_collum);
    ALTER TABLE projkanban.task ADD CONSTRAINT taskCdType FOREIGN KEY (cd_type) REFERENCES type(cd_type);
    ALTER TABLE projkanban.task ADD CONSTRAINT taskCdResponsable FOREIGN KEY (cd_responsable) REFERENCES responsable(cd_responsable);
    */

    INSERT INTO type VALUES(NULL, "Tipo Teste1");
    INSERT INTO type VALUES(NULL, "Tipo Teste2");
    INSERT INTO responsable VALUES(NULL, "Pessoa1");
    INSERT INTO responsable VALUES(NULL, "Pessoa2");
    INSERT INTO collum VALUES(NULL, "À Fazer", 1);
    INSERT INTO collum VALUES(NULL, "Fazendo", 2);
    INSERT INTO collum VALUES(NULL, "Feito", 3);
    INSERT INTO collum VALUES(NULL, "Pronto", 4);
    INSERT INTO task VALUES(NULL, "Tarefa Teste", 1, (SELECT cd_collum FROM collum WHERE cd_collum = 1), (SELECT cd_type FROM type WHERE cd_type = 1), (SELECT cd_responsable FROM responsable WHERE cd_responsable = 1));