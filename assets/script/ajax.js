async function sendAjaxRequest(url, method = "POST", data = {}) {
    try {
        // Check if the data is FormData (which means it contains files)
        const headers = data instanceof FormData ? {} : { "Content-Type": "application/json" };

        // If it's FormData, just send it directly without stringifying it
        const body = data instanceof FormData ? data : JSON.stringify(data);

        const response = await fetch(url, {
            method: method,
            headers: headers, // Don't set Content-Type for FormData
            body: body
        });

        return await response.json(); // ✅ Return parsed JSON response
    } catch (error) {
        console.error("❌ AJAX Error:", error);
        return { success: false, message: "Request failed" };
    }
}
