from fastapi import FastAPI

app = FastAPI()

@app.post("/getpic.cgi")
def getpic():
    return "getpic is not implemented"
