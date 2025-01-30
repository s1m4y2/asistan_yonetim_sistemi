-- Kullanici Tablosu
CREATE TABLE Kullanicilar (
    ID INT PRIMARY KEY IDENTITY,
    KullaniciAdi NVARCHAR(50) NOT NULL UNIQUE,
    Sifre NVARCHAR(50) NOT NULL,
	AdSoyad NVARCHAR(100) NOT NULL,
	RolType NVARCHAR(100) NOT NULL
);

-- Grup Yonetici Tablosu
CREATE TABLE GrupYoneticileri (
    ID INT PRIMARY KEY IDENTITY,
    KullaniciId INT NOT NULL,
	FOREIGN KEY (KullaniciId) REFERENCES Kullanicilar(ID)

);

-- Takim Liderleri Tablosu
CREATE TABLE TakimLiderleri (
    ID INT PRIMARY KEY IDENTITY,
    GrupYoneticisiID INT NOT NULL,
	KullaniciId INT NOT NULL,
	FOREIGN KEY (KullaniciId) REFERENCES Kullanicilar(ID),
    FOREIGN KEY (GrupYoneticisiID) REFERENCES GrupYoneticileri(ID)
);

-- Asistanlar Tablosu
CREATE TABLE Asistanlar (
    ID INT PRIMARY KEY IDENTITY,
    TakimLideriID INT NOT NULL,
	KullaniciId INT NOT NULL,
	FOREIGN KEY (KullaniciId) REFERENCES Kullanicilar(ID),
    FOREIGN KEY (TakimLideriID) REFERENCES TakimLiderleri(ID)
);

-- Gorusmeler Tablosu
CREATE TABLE Gorusmeler (
    ID INT PRIMARY KEY IDENTITY,
    AsistanSicilNo INT NOT NULL,
    MusteriAdSoyad NVARCHAR(100) NOT NULL,
    GorusmeKonusu NVARCHAR(50),
    GorusmeTarihi DATE NOT NULL,
    BaslamaSaati TIME NOT NULL,
    BitisSaati TIME NOT NULL,
    GorusmeDurumu NVARCHAR(50),
    FOREIGN KEY (AsistanSicilNo) REFERENCES Asistanlar(ID)
);

-- Primler Tablosu
CREATE TABLE Primler (
    ID INT PRIMARY KEY IDENTITY,
    AsistanSicilNo INT NOT NULL,
    Ay INT NOT NULL,
    Yil INT NOT NULL,
    PrimMiktari DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (AsistanSicilNo) REFERENCES Asistanlar(ID)
);

-- Itirazlar Tablosu
CREATE TABLE Itirazlar (
    ID INT PRIMARY KEY IDENTITY,
    AsistanSicilNo INT NOT NULL,
    PrimID INT NOT NULL,
    ItirazAciklamasi NVARCHAR(255) NOT NULL,
    ItirazCevabi NVARCHAR(255),
    ItirazDurumu NVARCHAR(50)  DEFAULT 'Bekliyor',
    FOREIGN KEY (AsistanSicilNo) REFERENCES Asistanlar(ID),
    FOREIGN KEY (PrimID) REFERENCES Primler(ID)
);


-- Login Log Tablosu
CREATE TABLE LoginLog (
    ID INT PRIMARY KEY IDENTITY,
    KullaniciID INT NOT NULL,
    GirisTarihi DATETIME NOT NULL,
    IP NVARCHAR(50),
    FOREIGN KEY (KullaniciID) REFERENCES Kullanicilar(ID)
);

-- Prim Log Tablosu
CREATE TABLE PrimLog (
    ID INT PRIMARY KEY IDENTITY,
    PrimID INT NOT NULL,
    DegisiklikTarihi DATETIME NOT NULL DEFAULT GETDATE(),
    EskiMiktar DECIMAL(10, 2),
    YeniMiktar DECIMAL(10, 2),
    FOREIGN KEY (PrimID) REFERENCES Primler(ID)
);

-- Itiraz Log Tablosu
CREATE TABLE ItirazLog (
    ID INT PRIMARY KEY IDENTITY,
    ItirazID INT NOT NULL,
    DegisiklikTarihi DATETIME NOT NULL DEFAULT GETDATE(),
    EskiDurum NVARCHAR(50),
    YeniDurum NVARCHAR(50),
    FOREIGN KEY (ItirazID) REFERENCES Itirazlar(ID)
);
USE proje
ALTER TABLE Kullanicilar
ADD Email NVARCHAR(255);
USE proje
UPDATE Kullanicilar
SET Email = 'takimlideri111@gmail.com'
WHERE ID = 2; 

USE proje
ALTER TABLE Kullanicilar
ADD Email NVARCHAR(255);
USE proje
UPDATE Kullanicilar
SET Email = 'grupyoneticisi646@gmail.com'
WHERE ID = 1; 