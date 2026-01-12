const { useState, useEffect } = React;

const SchemaMetaBox = () => {
    const [schema, setSchema] = useState(postSchemaData?.schema || '');
    const [enabled, setEnabled] = useState(postSchemaData?.enabled !== false);
    const [saving, setSaving] = useState(false);
    const editorRef = React.useRef(null);

    const saveSchema = async () => {
        setSaving(true);
        try {
            const response = await fetch(modularSchema.apiUrl + 'post/' + modularSchema.currentPostId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': modularSchema.nonce,
                },
                body: JSON.stringify({
                    schema: schema,
                    enabled: enabled,
                }),
            });
            const data = await response.json();
            
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Schema saved successfully',
                    timer: 2000,
                    showConfirmButton: false,
                });
            } else {
                throw new Error(data.message || 'Failed to save');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to save schema',
            });
        } finally {
            setSaving(false);
        }
    };

    const ToggleSwitch = ({ enabled, onChange, label }) => (
        <div className="schema-toggle-wrapper">
            <label className="schema-toggle-label">{label}</label>
            <label className="schema-toggle">
                <input
                    type="checkbox"
                    checked={enabled}
                    onChange={(e) => onChange(e.target.checked)}
                />
                <span className="schema-toggle-slider"></span>
            </label>
        </div>
    );

    const SchemaEditor = ({ value, onChange, placeholder = 'Paste your schema JSON-LD code here...' }) => (
        <div className="schema-editor-wrapper">
            <textarea
                ref={editorRef}
                className="schema-editor"
                value={value}
                onChange={(e) => onChange(e.target.value)}
                placeholder={placeholder}
                spellCheck={false}
            />
            <div className="schema-editor-info">
                <span className="schema-editor-hint">
                    💡 Tip: Paste your JSON-LD schema code here. It will be automatically wrapped in &lt;script type="application/ld+json"&gt; tags.
                </span>
            </div>
        </div>
    );

    return (
        <div className="schema-meta-box">
            <div className="schema-meta-box-content">
                <ToggleSwitch
                    enabled={enabled}
                    onChange={setEnabled}
                    label="Enable Schema for this post"
                />
                {enabled && (
                    <div className="schema-editor-section">
                        <label className="schema-label">Schema JSON-LD Code</label>
                        <SchemaEditor
                            value={schema}
                            onChange={setSchema}
                        />
                    </div>
                )}
                <div className="schema-actions">
                    <button
                        className="schema-btn schema-btn-primary"
                        onClick={saveSchema}
                        disabled={saving}
                    >
                        {saving ? 'Saving...' : 'Save Schema'}
                    </button>
                </div>
            </div>
        </div>
    );
};

// Render the meta box component
if (document.getElementById('modular-schema-meta-box')) {
    const root = ReactDOM.createRoot(document.getElementById('modular-schema-meta-box'));
    root.render(<SchemaMetaBox />);
}

