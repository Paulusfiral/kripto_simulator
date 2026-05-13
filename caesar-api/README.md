# Caesar Cipher — Microservice

## Cara Kerja

Folder ini berisi Python microservice untuk algoritma Caesar Cipher.
Strukturnya mengikuti pola yang sama dengan `chacha20-api/`.

## File yang Perlu Dikerjakan

| File               | Status         | Keterangan                         |
| ------------------ | -------------- | ---------------------------------- |
| `caesar.py`        | ✅ Sudah ada   | Implementasi Caesar Cipher core    |
| `main.py`          | ✅ Sudah ada   | FastAPI endpoints untuk service    |
| `cli.py`           | ✅ Ditambahkan | Terminal CLI untuk encrypt/decrypt |
| `requirements.txt` | ✅ Sudah ada   | Dependencies Python                |
| `test_caesar.py`   | ✅ Ditambahkan | Unit tests untuk fungsi Caesar     |
| `test_api.py`      | ✅ Ditambahkan | API integration tests              |

## Cara Menjalankan

```bash
cd caesar-api
pip install -r requirements.txt
python -m uvicorn main:app --host 127.0.0.1 --port 8002
```

Buka docs: http://127.0.0.1:8002/docs

### Jalankan CLI

```bash
python cli.py encrypt "Halo" 3
python cli.py decrypt "Kdor" 3
```

### Tes

```bash
python test_caesar.py
python test_api.py
```

## Aturan Penting

> ⚠️ **JANGAN hapus atau edit file di luar folder `caesar-api/` dan file Laravel Caesar kalian.**
> File ChaCha20 dan file tim lain JANGAN DISENTUH.

## Port

- ChaCha20 jalan di port `8001`
- Caesar Cipher jalan di port `8002` (jangan bentrok)
