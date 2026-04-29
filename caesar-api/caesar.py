"""
Caesar Cipher — Pure Python Implementation

TODO: Tim Caesar Cipher, implementasikan algoritma di sini!

Referensi:
  - Caesar Cipher adalah substitution cipher klasik
  - Setiap huruf digeser sebanyak `shift` posisi dalam alfabet
  - Contoh: shift=3 → A→D, B→E, C→F, ...
"""


def caesar_encrypt(plaintext: str, shift: int = 3) -> str:
    """
    Enkripsi plaintext menggunakan Caesar Cipher.

    Args:
        plaintext: Teks yang akan dienkripsi
        shift:     Jumlah pergeseran huruf (default: 3)

    Returns:
        Ciphertext hasil enkripsi

    TODO: Implementasikan logika enkripsi Caesar Cipher di sini!
    """
    # TODO: Ganti kode di bawah dengan implementasi kalian
    raise NotImplementedError("Belum diimplementasikan — kerjakan di sini!")


def caesar_decrypt(ciphertext: str, shift: int = 3) -> str:
    """
    Dekripsi ciphertext menggunakan Caesar Cipher.

    Args:
        ciphertext: Teks yang akan didekripsi
        shift:      Jumlah pergeseran huruf (harus sama saat enkripsi)

    Returns:
        Plaintext hasil dekripsi

    TODO: Implementasikan logika dekripsi Caesar Cipher di sini!
    """
    # TODO: Ganti kode di bawah dengan implementasi kalian
    raise NotImplementedError("Belum diimplementasikan — kerjakan di sini!")
