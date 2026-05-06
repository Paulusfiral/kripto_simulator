"""
Caesar Cipher Microservice — FastAPI
Kripto Simulator 2026 | Josua Reynold Tampubolon

Endpoints:
    GET  /          → Service info
    POST /encrypt   → Encrypt plaintext with Caesar Cipher
    POST /decrypt   → Decrypt ciphertext with Caesar Cipher
"""

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field

from caesar import caesar_encrypt, caesar_decrypt, spelling_alphabet
from transform.encoding import to_hex, to_base64
from transform.text_utils import normalize_text, count_chars


# ─────────────────────────────────────────────
#  FastAPI App
# ─────────────────────────────────────────────

app = FastAPI(
    title="Caesar Cipher Microservice",
    description="Implementasi Caesar Cipher untuk edukasi kriptografi.",
    version="1.0.0",
    docs_url="/docs",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


# ─────────────────────────────────────────────
#  Request / Response Schemas
# ─────────────────────────────────────────────

class EncryptRequest(BaseModel):
    plaintext: str = Field(..., description="Teks yang akan dienkripsi")
    shift: int     = Field(3,   description="Jumlah pergeseran huruf (default: 3)")


class DecryptRequest(BaseModel):
    ciphertext: str = Field(..., description="Teks yang akan didekripsi")
    shift: int      = Field(3,  description="Jumlah pergeseran (harus sama saat enkripsi)")


# ─────────────────────────────────────────────
#  Endpoints
# ─────────────────────────────────────────────

@app.get("/", tags=["Info"])
async def root():
    """Service information."""
    return {
        "service"  : "Caesar Cipher Microservice",
        "version"  : "1.0.0",
        "algorithm": {
            "name"         : "Caesar Cipher",
            "type"         : "Substitution Cipher",
            "default_shift": 3,
        },
    }


@app.post("/encrypt", tags=["Crypto"])
async def encrypt(req: EncryptRequest):
    """Encrypt plaintext using Caesar Cipher."""
    if not req.plaintext.strip():
        raise HTTPException(status_code=400, detail="Plaintext tidak boleh kosong")

    # Normalisasi input dulu
    clean_text = normalize_text(req.plaintext)

    try:
        ciphertext = caesar_encrypt(clean_text, req.shift)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

    return {
        "algorithm"  : "caesar",
        "plaintext"  : clean_text,
        "ciphertext" : ciphertext,
        "shift"      : req.shift,
        "spelling"   : spelling_alphabet(ciphertext),
        "encoding"   : {
            "hex"    : to_hex(ciphertext),
            "base64" : to_base64(ciphertext),
        },
        "stats"      : count_chars(clean_text),
    }


@app.post("/decrypt", tags=["Crypto"])
async def decrypt(req: DecryptRequest):
    """Decrypt ciphertext using Caesar Cipher."""
    if not req.ciphertext.strip():
        raise HTTPException(status_code=400, detail="Ciphertext tidak boleh kosong")

    clean_cipher = normalize_text(req.ciphertext)

    try:
        plaintext = caesar_decrypt(clean_cipher, req.shift)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

    return {
        "algorithm" : "caesar",
        "plaintext" : plaintext,
        "ciphertext": clean_cipher,
        "shift"     : req.shift,
        "spelling"  : spelling_alphabet(plaintext),
        "encoding"  : {
            "hex"   : to_hex(plaintext),
            "base64": to_base64(plaintext),
        },
        "stats"     : count_chars(plaintext),
    }