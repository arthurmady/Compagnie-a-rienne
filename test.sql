CREATE TABLE Billet_vol (
refb INTEGER REFERENCES Billet ON DELETE CASCADE,
refv VARCHAR(10) REFERENCES Vol ON DELETE CASCADE);
