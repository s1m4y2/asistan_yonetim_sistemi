USE proje
GO
CREATE VIEW vw_AsistanGorusmeVePrimler
AS
SELECT 
    A.ID AS AsistanID,
    K.AdSoyad AS AsistanAdSoyad,
    G.ID AS GorusmeID,
    G.MusteriAdSoyad,
    G.GorusmeKonusu,
    G.GorusmeTarihi,
    G.BaslamaSaati,
    G.BitisSaati,
    G.GorusmeDurumu,
    CASE 
        WHEN COUNT(G.ID) OVER (PARTITION BY G.GorusmeTarihi) < 100 THEN 'Prim hak edişi yok'
        WHEN COUNT(G.ID) OVER (PARTITION BY G.GorusmeTarihi) >= 100 AND COUNT(G.ID) OVER (PARTITION BY G.GorusmeTarihi) < 200 THEN 'Hak edildi'
        WHEN COUNT(G.ID) OVER (PARTITION BY G.GorusmeTarihi) >= 200 THEN 'Hak edildi'
    END AS PrimHakEdisDurumu
FROM 
    Gorusmeler G
JOIN 
    Asistanlar A ON G.AsistanSicilNo = A.ID
JOIN 
    Kullanicilar K ON A.KullaniciId = K.ID;






GO

CREATE VIEW vw_ItirazlarVeDurumlari
AS
SELECT 
    I.ID AS ItirazID,
    A.ID AS AsistanID,
    K.AdSoyad AS AsistanAdSoyad,
    P.ID AS PrimID,
    P.Ay,
    P.Yil,
    P.PrimMiktari,
    I.ItirazAciklamasi,
    I.ItirazCevabi,
    I.ItirazDurumu
FROM 
    Itirazlar I
JOIN 
    Asistanlar A ON I.AsistanSicilNo = A.ID
JOIN 
    Kullanicilar K ON A.KullaniciId = K.ID
JOIN 
    Primler P ON I.PrimID = P.ID;

