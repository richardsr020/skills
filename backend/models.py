from pydantic import BaseModel, EmailStr
from typing import Optional
from datetime import datetime

class BetaUserCreate(BaseModel):
    firstName: Optional[str] = None
    lastName: Optional[str] = None
    email: EmailStr
    phone: str
    userType: Optional[str] = None
    consent: bool
    source: Optional[str] = "unknown"

class BetaUserResponse(BaseModel):
    id: int
    firstName: Optional[str]
    lastName: Optional[str]
    email: str
    phone: str
    userType: Optional[str]
    source: str
    signup_date: datetime
    status: str

    class Config:
        from_attributes = True