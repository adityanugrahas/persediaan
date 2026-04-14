-- ============================================================
-- PostgreSQL Schema for Persediaan (Inventory Management)
-- Enhanced with indexes, constraints, and audit columns
-- ============================================================

-- -------------------------------------------------------
-- Table: setting
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS setting (
  setting_id   VARCHAR(20) PRIMARY KEY,
  title_head   VARCHAR(100) NOT NULL,
  nama_kantor  VARCHAR(150) NOT NULL,
  alamat_kantor VARCHAR(200) NOT NULL,
  telp_kantor  VARCHAR(20) NOT NULL,
  email_kantor VARCHAR(50) NOT NULL,
  logo_header  VARCHAR(50) NOT NULL,
  favicon      VARCHAR(50) NOT NULL DEFAULT 'favicon.png',
  created_at   TIMESTAMPTZ DEFAULT NOW(),
  updated_at   TIMESTAMPTZ DEFAULT NOW()
);

-- -------------------------------------------------------
-- Table: bagian (departments / sections)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS bagian (
  id_seksi    VARCHAR(25) PRIMARY KEY,
  induk       VARCHAR(35) NOT NULL,
  bagian      VARCHAR(35) NOT NULL,
  keterangan  TEXT NOT NULL DEFAULT '',
  urutan      INTEGER NOT NULL DEFAULT 0,
  created_at  TIMESTAMPTZ DEFAULT NOW()
);

-- -------------------------------------------------------
-- Table: petugas (users / operators)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS petugas (
  id_petugas VARCHAR(25) PRIMARY KEY,
  users_id   VARCHAR(100) NOT NULL,
  pwd        VARCHAR(255) NOT NULL,  -- widened for bcrypt hashes
  nama       VARCHAR(50) NOT NULL,
  seksi      VARCHAR(20) NOT NULL DEFAULT '',
  jabatan    VARCHAR(25) NOT NULL DEFAULT '',
  p_level    VARCHAR(10) NOT NULL DEFAULT 'opr',
  photo      VARCHAR(30) NOT NULL DEFAULT '',
  ttd        VARCHAR(30) NOT NULL DEFAULT '',
  last_in    VARCHAR(30) NOT NULL DEFAULT '',
  last_out   VARCHAR(30) NOT NULL DEFAULT '',
  urutan     INTEGER NOT NULL DEFAULT 0,
  p_status   VARCHAR(10) NOT NULL DEFAULT 'aktif',
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_petugas_users_id ON petugas(users_id);
CREATE INDEX IF NOT EXISTS idx_petugas_level    ON petugas(p_level);
CREATE INDEX IF NOT EXISTS idx_petugas_status   ON petugas(p_status);
CREATE INDEX IF NOT EXISTS idx_petugas_seksi    ON petugas(seksi);

-- -------------------------------------------------------
-- Table: kategori (stock item categories)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS kategori (
  id_kat   VARCHAR(20) PRIMARY KEY,
  editor   VARCHAR(30) NOT NULL DEFAULT '',
  nama_kat VARCHAR(30) NOT NULL,
  ket_kat  VARCHAR(250) NOT NULL DEFAULT '',
  status   VARCHAR(15) NOT NULL DEFAULT 'ON',
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- -------------------------------------------------------
-- Table: stok_barang (inventory items)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS stok_barang (
  id_barang    VARCHAR(25) PRIMARY KEY,
  kategori     VARCHAR(25) NOT NULL,
  nama_barang  VARCHAR(150) NOT NULL,
  jumlah_stok  INTEGER NOT NULL DEFAULT 0,
  satuan       VARCHAR(20) NOT NULL DEFAULT '',
  harga_satuan INTEGER NOT NULL DEFAULT 0,
  stok_minimal INTEGER NOT NULL DEFAULT 0,
  keterangan   TEXT NOT NULL DEFAULT '',
  gambar       VARCHAR(150) NOT NULL DEFAULT '',
  aktif        VARCHAR(10) NOT NULL DEFAULT 'ya',
  created_at   TIMESTAMPTZ DEFAULT NOW(),
  updated_at   TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_stok_barang_kategori ON stok_barang(kategori);
CREATE INDEX IF NOT EXISTS idx_stok_barang_aktif    ON stok_barang(aktif);
CREATE INDEX IF NOT EXISTS idx_stok_barang_nama     ON stok_barang(nama_barang);
CREATE INDEX IF NOT EXISTS idx_stok_barang_lowstock ON stok_barang(jumlah_stok, stok_minimal) WHERE aktif = 'ya';

-- -------------------------------------------------------
-- Table: anggaran (budget)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS anggaran (
  id_anggaran      VARCHAR(25) PRIMARY KEY,
  tahun_anggaran   VARCHAR(10) NOT NULL,
  akun_anggaran    VARCHAR(20) NOT NULL,
  ket_anggaran     VARCHAR(150) NOT NULL DEFAULT '',
  pagu_anggaran    INTEGER NOT NULL DEFAULT 0,
  serapan_anggaran INTEGER NOT NULL DEFAULT 0,
  status           INTEGER NOT NULL DEFAULT 0,
  created_at       TIMESTAMPTZ DEFAULT NOW(),
  updated_at       TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_anggaran_tahun  ON anggaran(tahun_anggaran);
CREATE INDEX IF NOT EXISTS idx_anggaran_status ON anggaran(status);

-- -------------------------------------------------------
-- Table: nota (transaction records / receipts)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS nota (
  id_nota     VARCHAR(35) PRIMARY KEY,
  kode_nota   VARCHAR(35) NOT NULL DEFAULT '',
  jenis       VARCHAR(20) NOT NULL,
  id_anggaran VARCHAR(25) NOT NULL DEFAULT '',
  tanggal     TIMESTAMP NOT NULL DEFAULT NOW(),
  pemroses    VARCHAR(35) NOT NULL,
  jum_out     INTEGER NOT NULL DEFAULT 0,
  jum_in      INTEGER NOT NULL DEFAULT 0,
  keterangan  VARCHAR(250) NOT NULL DEFAULT '',
  lampiran    VARCHAR(50) NOT NULL DEFAULT '',
  status      VARCHAR(10) NOT NULL DEFAULT '',
  created_at  TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_nota_kode     ON nota(kode_nota);
CREATE INDEX IF NOT EXISTS idx_nota_anggaran ON nota(id_anggaran);
CREATE INDEX IF NOT EXISTS idx_nota_tanggal  ON nota(tanggal);

-- -------------------------------------------------------
-- Table: stok_inout (stock movements in/out)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS stok_inout (
  id_inout    VARCHAR(25) PRIMARY KEY,
  jenis       VARCHAR(10) NOT NULL,
  kode_nota   VARCHAR(25) NOT NULL DEFAULT '',
  petugas     VARCHAR(25) NOT NULL,
  id_seksi    VARCHAR(25) NOT NULL DEFAULT '',
  tgl         TIMESTAMP NOT NULL DEFAULT NOW(),
  id_barang   VARCHAR(25) NOT NULL,
  jml_req     INTEGER NOT NULL DEFAULT 0,
  jml_in      INTEGER NOT NULL DEFAULT 0,
  jml_out     INTEGER NOT NULL DEFAULT 0,
  satuan      VARCHAR(25) NOT NULL DEFAULT '',
  jml_stok    INTEGER NOT NULL DEFAULT 0,
  harga       INTEGER NOT NULL DEFAULT 0,
  sumber_dana VARCHAR(30) NOT NULL DEFAULT '',
  keterangan  VARCHAR(250) NOT NULL DEFAULT '',
  catatan     VARCHAR(300) NOT NULL DEFAULT '',
  status      VARCHAR(4) NOT NULL DEFAULT '0',
  tgl_ok      TIMESTAMP,
  lampiran    VARCHAR(150) NOT NULL DEFAULT '',
  created_at  TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_stok_inout_jenis    ON stok_inout(jenis);
CREATE INDEX IF NOT EXISTS idx_stok_inout_status   ON stok_inout(status);
CREATE INDEX IF NOT EXISTS idx_stok_inout_petugas  ON stok_inout(petugas);
CREATE INDEX IF NOT EXISTS idx_stok_inout_barang   ON stok_inout(id_barang);
CREATE INDEX IF NOT EXISTS idx_stok_inout_kode     ON stok_inout(kode_nota);
CREATE INDEX IF NOT EXISTS idx_stok_inout_tgl      ON stok_inout(tgl);
CREATE INDEX IF NOT EXISTS idx_stok_inout_pending  ON stok_inout(status) WHERE status IN ('0', '1');

-- -------------------------------------------------------
-- Table: pesanan (orders / requests for items)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS pesanan (
  id_pesanan  VARCHAR(25) PRIMARY KEY,
  id_seksi    VARCHAR(25) NOT NULL,
  petugas     VARCHAR(25) NOT NULL,
  id_barang   VARCHAR(25) NOT NULL DEFAULT '',
  nama_barang VARCHAR(50) NOT NULL DEFAULT '',
  ket_barang  VARCHAR(100) NOT NULL DEFAULT '',
  jumlah      INTEGER NOT NULL DEFAULT 0,
  satuan      VARCHAR(25) NOT NULL DEFAULT '',
  tgl_pesan   VARCHAR(25) NOT NULL DEFAULT '',
  keterangan  VARCHAR(50) NOT NULL DEFAULT '',
  lampiran    VARCHAR(50) NOT NULL DEFAULT '',
  jumlah_stok INTEGER NOT NULL DEFAULT 0,
  created_at  TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_pesanan_petugas ON pesanan(petugas);
CREATE INDEX IF NOT EXISTS idx_pesanan_stok    ON pesanan(jumlah_stok);

-- -------------------------------------------------------
-- Table: notif (notifications)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS notif (
  id_notif    VARCHAR(30) PRIMARY KEY,
  tgl_notif   TIMESTAMP NOT NULL DEFAULT NOW(),
  id_seksi    VARCHAR(20) NOT NULL DEFAULT '',
  id_petugas  VARCHAR(20) NOT NULL,
  id_barang   VARCHAR(20) NOT NULL,
  status      VARCHAR(1) NOT NULL DEFAULT '0',
  keterangan  VARCHAR(250) NOT NULL DEFAULT '',
  created_at  TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_notif_petugas ON notif(id_petugas);
CREATE INDEX IF NOT EXISTS idx_notif_status  ON notif(status);

-- -------------------------------------------------------
-- Table: kat_bmn (BMN categories)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS kat_bmn (
  id_kat   VARCHAR(20) PRIMARY KEY,
  editor   VARCHAR(30) NOT NULL DEFAULT '',
  nama_kat VARCHAR(30) NOT NULL,
  ket_kat  VARCHAR(250) NOT NULL DEFAULT '',
  status   VARCHAR(15) NOT NULL DEFAULT 'ON',
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- -------------------------------------------------------
-- Table: bmn (Barang Milik Negara — government assets)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS bmn (
  id_bmn       VARCHAR(25) PRIMARY KEY,
  kat_bmn      VARCHAR(25) NOT NULL,
  kode_bmn     VARCHAR(30) NOT NULL DEFAULT '',
  nama_bmn     VARCHAR(50) NOT NULL,
  jumlah_bmn   INTEGER NOT NULL DEFAULT 0,
  satuan       VARCHAR(10) NOT NULL DEFAULT '',
  harga_satuan INTEGER NOT NULL DEFAULT 0,
  asal_oleh    VARCHAR(50) NOT NULL DEFAULT '',
  tgl_oleh     DATE,
  bukti_oleh   VARCHAR(50) NOT NULL DEFAULT '',
  harga_oleh   INTEGER NOT NULL DEFAULT 0,
  stok_minimal INTEGER NOT NULL DEFAULT 0,
  keterangan   VARCHAR(50) NOT NULL DEFAULT '',
  gambar       VARCHAR(150) NOT NULL DEFAULT '',
  aktif        VARCHAR(10) NOT NULL DEFAULT 'ya',
  pengguna     VARCHAR(100) NOT NULL DEFAULT '',
  lokasi       VARCHAR(50) NOT NULL DEFAULT '',
  kondisi      VARCHAR(50) NOT NULL DEFAULT '',
  created_at   TIMESTAMPTZ DEFAULT NOW(),
  updated_at   TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_bmn_kat   ON bmn(kat_bmn);
CREATE INDEX IF NOT EXISTS idx_bmn_aktif ON bmn(aktif);

-- -------------------------------------------------------
-- Table: bmn_dist (BMN distribution / usage log)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS bmn_dist (
  id_dist        VARCHAR(25) PRIMARY KEY,
  id_bmn         VARCHAR(30) NOT NULL,
  jumlah_pakai   INTEGER NOT NULL DEFAULT 0,
  jumlah_kembali INTEGER NOT NULL DEFAULT 0,
  keterangan     VARCHAR(50) NOT NULL DEFAULT '',
  seksi          VARCHAR(30) NOT NULL DEFAULT '',
  pengguna       VARCHAR(100) NOT NULL DEFAULT '',
  lokasi         VARCHAR(50) NOT NULL DEFAULT '',
  kondisi        VARCHAR(50) NOT NULL DEFAULT '',
  tgl_dist       TIMESTAMP NOT NULL DEFAULT NOW(),
  nama_lampiran  VARCHAR(30) NOT NULL DEFAULT '',
  lampiran       VARCHAR(30) NOT NULL DEFAULT '',
  created_at     TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_bmn_dist_bmn ON bmn_dist(id_bmn);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Default bagian (sections)
INSERT INTO bagian (id_seksi, induk, bagian, keterangan, urutan) VALUES
('20210213112744', 'root', 'KAKANIM', 'Kepala Kantor', 0),
('210510023455', '20210213112744', 'TU', 'Sub Bag Tata Usaha', 0),
('210521030442', '20210213112744', 'TIKKIM', 'Teknologi Informasi dan Komunikasi Keimigrasian', 0),
('210521025455', '20210213112744', 'VERDOKJAL', 'Pelayanan dan Verifikasi Dokumen Perjalanan', 0),
('210521025819', '20210213112744', 'INTELDAKIM', 'Intelijen dan Penindakan Keimigrasian', 0),
('210521033523', '20210213112744', 'INTALTUSKIM', 'Izin Tinggal dan Status Keimigrasian', 0)
ON CONFLICT (id_seksi) DO NOTHING;

-- Default setting
INSERT INTO setting (setting_id, title_head, nama_kantor, alamat_kantor, telp_kantor, email_kantor, logo_header) VALUES
('20210212131943', 'IMIGRASI KARAWANG', 'KANTOR IMIGRASI KELAS I NON TPI KARAWANG', 'JL. JEND. AHAMAD YANI  NO 18 (BY PASS) KARAWANG', '081222311607', 'kanim2karawang@gmail.com', '210524032446-logo.png')
ON CONFLICT (setting_id) DO NOTHING;

-- Default admin user (password: admin — will be hashed on first login)
INSERT INTO petugas (id_petugas, users_id, pwd, nama, seksi, jabatan, p_level, photo, ttd, last_in, last_out, urutan, p_status) VALUES
('20210212134326', 'admin', 'admin', 'Administrator', '210510023455', '', 'su', '', '', '', '', 0, 'aktif')
ON CONFLICT (id_petugas) DO NOTHING;

-- ============================================================
-- MIGRATION HELPERS (run once for existing data)
-- ============================================================

-- Add audit columns to existing tables if they don't exist
DO $$
BEGIN
    -- Add created_at to tables missing it
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='petugas' AND column_name='created_at') THEN
        ALTER TABLE petugas ADD COLUMN created_at TIMESTAMPTZ DEFAULT NOW();
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='petugas' AND column_name='updated_at') THEN
        ALTER TABLE petugas ADD COLUMN updated_at TIMESTAMPTZ DEFAULT NOW();
    END IF;

    -- Widen pwd column if needed
    ALTER TABLE petugas ALTER COLUMN pwd TYPE VARCHAR(255);
    ALTER TABLE petugas ALTER COLUMN users_id TYPE VARCHAR(100);

EXCEPTION WHEN OTHERS THEN
    RAISE NOTICE 'Migration adjustments skipped (may already exist): %', SQLERRM;
END $$;
