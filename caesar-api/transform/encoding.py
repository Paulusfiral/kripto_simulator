"""
Modul Encoding & Decoding
Kripto Simulator 2026 | Josua Reynold Tampubolon
Fungsi: konversi teks ke HEX dan Base64
"""

import base64


def to_hex(text: str) -> str:
    """
    Konversi teks ke representasi HEX.

    Contoh:
        to_hex("Hello") -> "48656c6c6f"
    """
    return text.encode('utf-8').hex()


def from_hex(hex_string: str) -> str:
    """
    Konversi HEX kembali ke teks.

    Contoh:
        from_hex("48656c6c6f") -> "Hello"
    """
    try:
        return bytes.fromhex(hex_string).decode('utf-8')
    except (ValueError, UnicodeDecodeError) as e:
        raise ValueError(f"HEX tidak valid: {e}")


def to_base64(text: str) -> str:
    """
    Konversi teks ke Base64.

    Contoh:
        to_base64("Hello") -> "SGVsbG8="
    """
    return base64.b64encode(text.encode('utf-8')).decode('utf-8')


def from_base64(base64_string: str) -> str:
    """
    Konversi Base64 kembali ke teks.

    Contoh:
        from_base64("SGVsbG8=") -> "Hello"
    """
    try:
        return base64.b64decode(base64_string.encode('utf-8')).decode('utf-8')
    except Exception as e:
        raise ValueError(f"Base64 tidak valid: {e}")


# ── Quick test ────────────────────────────────────────────
if __name__ == "__main__":
    sample = "Hello, Kripto!"
    print(f"Original  : {sample}")
    print(f"HEX       : {to_hex(sample)}")
    print(f"From HEX  : {from_hex(to_hex(sample))}")
    print(f"Base64    : {to_base64(sample)}")
    print(f"From B64  : {from_base64(to_base64(sample))}")