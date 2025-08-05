const { default: axios } = require("axios");
const sum = require("./libs.js")
const api = "http://localhost:8245/api-analiticsdata/public"

test('Recupera um code para gerar o QRCode pelo Ip que nao Foi Usado', async () => {
    const response = await axios.get(api + "/qrcode/read_qrcode")
    expect(response.data.result.qrcode).toBeDefined();
    expect(response.data.result.qrcode).not.toBe(""); // ou:
    expect(response.data.result.qrcode).toMatch(/[a-z0-9\-]+/i);
});

test("Usar o qrcode", async () => {
    const response = await axios.get(api + "/qrcode/read_qrcode")
    expect(response.data.result.qrcode).toBeDefined();
    expect(response.data.result.qrcode).not.toBe(""); // ou:
    expect(response.data.result.qrcode).toMatch(/[a-z0-9\-]+/i);
    const input = {
        "qrcode": response.data.result.qrcode
    }
    const response_qrcode_used = await axios.post(api + "/qrcode/used_qrcode", JSON.stringify(input), { validateStatus: false })
    expect(response_qrcode_used.data.state).toBe(201)



})

