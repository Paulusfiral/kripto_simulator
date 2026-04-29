"""
Caesar Cipher Microservice — FastAPI

Endpoints:
    GET  /          → Service info
    POST /encrypt   → Encrypt plaintext with Caesar Cipher
    POST /decrypt   → Decrypt ciphertext with Caesar Cipher

TODO: Tim Caesar Cipher, sesuaikan endpoint-endpoint ini!
      Struktur ini mengikuti pola yang sama dengan chacha20-api/main.py
"""

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import Optional

from caesar import caesar_encrypt, caesar_decrypt


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
    shift: int = Field(3, description="Jumlah pergeseran huruf (default: 3)")


class EncryptResponse(BaseModel):
    ciphertext: str
    shift: int
    plaintext_length: int
    ciphertext_length: int


class DecryptRequest(BaseModel):
    ciphertext: str = Field(..., description="Teks yang akan didekripsi")
    shift: int = Field(3, description="Jumlah pergeseran huruf (harus sama saat enkripsi)")


class DecryptResponse(BaseModel):
    plaintext: str
    shift: int


# ─────────────────────────────────────────────
#  Endpoints
# ─────────────────────────────────────────────

@app.get("/", tags=["Info"])
async def root():
    """Service information."""
    return {
        "service": "Caesar Cipher Microservice",
        "version": "1.0.0",
        "algorithm": {
            "name": "Caesar Cipher",
            "type": "Substitution Cipher",
            "default_shift": 3,
        },
    }


@app.post("/encrypt", response_model=EncryptResponse, tags=["Crypto"])
async def encrypt(req: EncryptRequest):
    """Encrypt plaintext using Caesar Cipher."""
    if not req.plaintext:
        raise HTTPException(status_code=400, detail="Plaintext tidak boleh kosong")

    try:
        ciphertext = caesar_encrypt(req.plaintext, req.shift)
    except NotImplementedError as e:
        raise HTTPException(status_code=501, detail=str(e))

    return EncryptResponse(
        ciphertext=ciphertext,
        shift=req.shift,
        plaintext_length=len(req.plaintext),
        ciphertext_length=len(ciphertext),
    )


@app.post("/decrypt", response_model=DecryptResponse, tags=["Crypto"])
async def decrypt(req: DecryptRequest):
    """Decrypt ciphertext using Caesar Cipher."""
    if not req.ciphertext:
        raise HTTPException(status_code=400, detail="Ciphertext tidak boleh kosong")

    try:
        plaintext = caesar_decrypt(req.ciphertext, req.shift)
    except NotImplementedError as e:
        raise HTTPException(status_code=501, detail=str(e))

    return DecryptResponse(
        plaintext=plaintext,
        shift=req.shift,
    )
