USE proje
GO
CREATE TRIGGER trg_AylikPrimOlustur
ON Asistanlar
AFTER INSERT
AS
BEGIN
    DECLARE @AsistanID INT;

    -- Yeni eklenen asistan�n ID'sini al
    SELECT @AsistanID = ID FROM inserted;

    -- Yeni asistana ait ayl�k prim listesini olu�tur
    INSERT INTO Primler (AsistanSicilNo, Ay, Yil, PrimMiktari)
    VALUES (@AsistanID, MONTH(GETDATE()), YEAR(GETDATE()), 0); -- Varsay�lan prim miktar� 0 olarak eklenir
END;
GO

USE proje
GO
/****** Object:  Trigger [dbo].[trg_AylikPrimOlustur]    Script Date: 18/05/2024 19:28:34 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER TRIGGER [dbo].[trg_AylikPrimOlustur]
ON [dbo].[Asistanlar]
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    -- Yeni eklenen asistanlar�n ID'lerini almak i�in cursor kullan
    DECLARE @AsistanID INT;

    DECLARE AsistanCursor CURSOR FOR
    SELECT ID
    FROM inserted;

    OPEN AsistanCursor;
    FETCH NEXT FROM AsistanCursor INTO @AsistanID;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Yeni asistana ait ayl�k prim listesini olu�tur
        INSERT INTO Primler (AsistanSicilNo, Ay, Yil, PrimMiktari)
        VALUES (@AsistanID, MONTH(GETDATE()), YEAR(GETDATE()), 0); -- Varsay�lan prim miktar� 0 olarak eklenir

        FETCH NEXT FROM AsistanCursor INTO @AsistanID;
    END;

    CLOSE AsistanCursor;
    DEALLOCATE AsistanCursor;
END;
GO


CREATE TRIGGER trg_AfterInsertItiraz
ON Itirazlar
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    INSERT INTO ItirazLog (ItirazID, DegisiklikTarihi, EskiDurum, YeniDurum)
    SELECT 
        inserted.ID,
        GETDATE(),
        NULL,  -- EskiDurum ekleme i�leminde NULL olacak
        inserted.ItirazDurumu
    FROM inserted;
END;
GO

CREATE TRIGGER trg_AfterUpdateItiraz
ON Itirazlar
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;

    INSERT INTO ItirazLog (ItirazID, DegisiklikTarihi, EskiDurum, YeniDurum)
    SELECT 
        inserted.ID,
        GETDATE(),
        deleted.ItirazDurumu,
        inserted.ItirazDurumu
    FROM inserted
    JOIN deleted ON inserted.ID = deleted.ID;
END;
