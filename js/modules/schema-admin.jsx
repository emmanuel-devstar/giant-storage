const { useState, useEffect, useRef } = React;

const SchemaAdmin = () => {
    const [settings, setSettings] = useState({
        global_schema: '',
        global_enabled: false,
        page_schemas: [],
    });
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [activeTab, setActiveTab] = useState('global');
    const [postTypeFilter, setPostTypeFilter] = useState('any');
    const [postSearch, setPostSearch] = useState('');
    const [postsList, setPostsList] = useState([]);
    const [selectedPage, setSelectedPage] = useState(null);
    const [selectedPost, setSelectedPost] = useState(null);
    const [postSchema, setPostSchema] = useState({ schema: '', enabled: true });
    const editorRef = useRef(null);

    useEffect(() => {
        loadSettings();
    }, []);

    useEffect(() => {
        if (activeTab === 'pages' || activeTab === 'posts') {
            loadPosts();
        }
    }, [activeTab, postTypeFilter, postSearch]);

    const loadSettings = async () => {
        try {
            const response = await fetch(modularSchema.apiUrl + 'settings', {
                headers: {
                    'X-WP-Nonce': modularSchema.nonce,
                },
            });
            const data = await response.json();
            setSettings(data);
            setLoading(false);
        } catch (error) {
            console.error('Error loading settings:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load settings',
            });
            setLoading(false);
        }
    };

    const loadPosts = async () => {
        try {
            const params = new URLSearchParams({
                post_type: postTypeFilter,
            });
            if (postSearch) {
                params.append('search', postSearch);
            }
            const response = await fetch(modularSchema.apiUrl + 'posts?' + params, {
                headers: {
                    'X-WP-Nonce': modularSchema.nonce,
                },
            });
            const data = await response.json();
            setPostsList(data);
        } catch (error) {
            console.error('Error loading posts:', error);
        }
    };

    const loadPostSchema = async (postId) => {
        try {
            const response = await fetch(modularSchema.apiUrl + 'post/' + postId, {
                headers: {
                    'X-WP-Nonce': modularSchema.nonce,
                },
            });
            const data = await response.json();
            setPostSchema(data);
            setSelectedPost(postId);
        } catch (error) {
            console.error('Error loading post schema:', error);
        }
    };

    const saveSettings = async () => {
        setSaving(true);
        try {
            const response = await fetch(modularSchema.apiUrl + 'settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': modularSchema.nonce,
                },
                body: JSON.stringify(settings),
            });
            const data = await response.json();
            
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Settings saved successfully',
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
                text: error.message || 'Failed to save settings',
            });
        } finally {
            setSaving(false);
        }
    };

    const savePostSchema = async () => {
        if (!selectedPost) return;
        
        setSaving(true);
        try {
            const response = await fetch(modularSchema.apiUrl + 'post/' + selectedPost, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': modularSchema.nonce,
                },
                body: JSON.stringify(postSchema),
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

    const addPageSchema = () => {
        if (!selectedPage) {
            Swal.fire({
                icon: 'warning',
                title: 'No Page Selected',
                text: 'Please select a page first',
            });
            return;
        }

        const exists = settings.page_schemas.find(s => s.page_id === selectedPage.id);
        if (exists) {
            Swal.fire({
                icon: 'info',
                title: 'Already Added',
                text: 'This page already has schema configured',
            });
            return;
        }

        setSettings({
            ...settings,
            page_schemas: [
                ...settings.page_schemas,
                {
                    page_id: selectedPage.id,
                    schema: '',
                    enabled: true,
                },
            ],
        });
        setSelectedPage(null);
    };

    const removePageSchema = (pageId) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will remove the schema for this page',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffbb00',
            cancelButtonColor: '#8f8f8f',
            confirmButtonText: 'Yes, remove it!',
        }).then((result) => {
            if (result.isConfirmed) {
                setSettings({
                    ...settings,
                    page_schemas: settings.page_schemas.filter(s => s.page_id !== pageId),
                });
                Swal.fire('Removed!', 'Schema has been removed.', 'success');
            }
        });
    };

    const updatePageSchema = (pageId, field, value) => {
        setSettings({
            ...settings,
            page_schemas: settings.page_schemas.map(s =>
                s.page_id === pageId ? { ...s, [field]: value } : s
            ),
        });
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

    if (loading) {
        return (
            <div className="schema-loading">
                <div className="schema-spinner"></div>
                <p>Loading...</p>
            </div>
        );
    }

    return (
        <div className="schema-admin-container">
            <div className="schema-tabs">
                <button
                    className={`schema-tab ${activeTab === 'global' ? 'active' : ''}`}
                    onClick={() => setActiveTab('global')}
                >
                    <span className="dashicons dashicons-admin-site"></span>
                    Global Schema
                </button>
                <button
                    className={`schema-tab ${activeTab === 'pages' ? 'active' : ''}`}
                    onClick={() => setActiveTab('pages')}
                >
                    <span className="dashicons dashicons-admin-page"></span>
                    Page Schemas
                </button>
                <button
                    className={`schema-tab ${activeTab === 'posts' ? 'active' : ''}`}
                    onClick={() => setActiveTab('posts')}
                >
                    <span className="dashicons dashicons-admin-post"></span>
                    Post Schemas
                </button>
            </div>

            <div className="schema-content">
                {activeTab === 'global' && (
                    <div className="schema-panel">
                        <div className="schema-panel-header">
                            <h2>Global Schema Settings</h2>
                            <p>Add schema markup that will be applied to all pages across your site.</p>
                        </div>
                        <div className="schema-panel-body">
                            <ToggleSwitch
                                enabled={settings.global_enabled}
                                onChange={(val) => setSettings({ ...settings, global_enabled: val })}
                                label="Enable Global Schema"
                            />
                            {settings.global_enabled && (
                                <div className="schema-editor-section">
                                    <label className="schema-label">Schema JSON-LD Code</label>
                                    <SchemaEditor
                                        value={settings.global_schema}
                                        onChange={(val) => setSettings({ ...settings, global_schema: val })}
                                    />
                                </div>
                            )}
                            <div className="schema-actions">
                                <button
                                    className="schema-btn schema-btn-primary"
                                    onClick={saveSettings}
                                    disabled={saving}
                                >
                                    {saving ? 'Saving...' : 'Save Global Settings'}
                                </button>
                            </div>
                        </div>
                    </div>
                )}

                {activeTab === 'pages' && (
                    <div className="schema-panel">
                        <div className="schema-panel-header">
                            <h2>Page-Specific Schema</h2>
                            <p>Add schema markup for individual pages.</p>
                        </div>
                        <div className="schema-panel-body">
                            <div className="schema-add-page">
                                <h3>Add New Page Schema</h3>
                                <div className="schema-page-selector">
                                    <select
                                        className="schema-select"
                                        value={selectedPage?.id || ''}
                                        onChange={(e) => {
                                            const page = postsList.find(p => p.id === parseInt(e.target.value));
                                            setSelectedPage(page || null);
                                        }}
                                    >
                                        <option value="">Select a page...</option>
                                        {postsList
                                            .filter(p => p.type === 'page')
                                            .map(page => (
                                                <option key={page.id} value={page.id}>
                                                    {page.title} (ID: {page.id})
                                                </option>
                                            ))}
                                    </select>
                                    <button
                                        className="schema-btn schema-btn-secondary"
                                        onClick={addPageSchema}
                                        disabled={!selectedPage}
                                    >
                                        Add Page
                                    </button>
                                </div>
                            </div>

                            {settings.page_schemas.length > 0 && (
                                <div className="schema-list">
                                    <h3>Configured Page Schemas</h3>
                                    {settings.page_schemas.map((pageSchema, index) => {
                                        // Try to find page in current list, or fetch it
                                        let page = postsList.find(p => p.id === pageSchema.page_id);
                                        if (!page) {
                                            // If not in list, create a placeholder
                                            page = { id: pageSchema.page_id, title: `Page ID: ${pageSchema.page_id}`, type: 'page' };
                                        }
                                        return (
                                            <div key={pageSchema.page_id} className="schema-item">
                                                <div className="schema-item-header">
                                                    <div className="schema-item-title">
                                                        <strong>{page.title}</strong>
                                                        <a
                                                            href={`/wp-admin/post.php?post=${pageSchema.page_id}&action=edit`}
                                                            target="_blank"
                                                            className="schema-edit-link"
                                                        >
                                                            Edit Page
                                                        </a>
                                                    </div>
                                                    <button
                                                        className="schema-btn-remove"
                                                        onClick={() => removePageSchema(pageSchema.page_id)}
                                                        title="Remove"
                                                    >
                                                        <span className="dashicons dashicons-trash"></span>
                                                    </button>
                                                </div>
                                                <ToggleSwitch
                                                    enabled={pageSchema.enabled}
                                                    onChange={(val) => updatePageSchema(pageSchema.page_id, 'enabled', val)}
                                                    label="Enable Schema"
                                                />
                                                <div className="schema-editor-section">
                                                    <label className="schema-label">Schema JSON-LD Code</label>
                                                    <SchemaEditor
                                                        value={pageSchema.schema}
                                                        onChange={(val) => updatePageSchema(pageSchema.page_id, 'schema', val)}
                                                    />
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            )}

                            <div className="schema-actions">
                                <button
                                    className="schema-btn schema-btn-primary"
                                    onClick={saveSettings}
                                    disabled={saving}
                                >
                                    {saving ? 'Saving...' : 'Save Page Schemas'}
                                </button>
                            </div>
                        </div>
                    </div>
                )}

                {activeTab === 'posts' && (
                    <div className="schema-panel">
                        <div className="schema-panel-header">
                            <h2>Post Schema Management</h2>
                            <p>Add schema markup for individual posts, pages, and custom post types.</p>
                        </div>
                        <div className="schema-panel-body">
                            <div className="schema-post-filters">
                                <div className="schema-filter-group">
                                    <label className="schema-label">Post Type</label>
                                    <select
                                        className="schema-select"
                                        value={postTypeFilter}
                                        onChange={(e) => {
                                            setPostTypeFilter(e.target.value);
                                            setSelectedPost(null);
                                        }}
                                    >
                                        <option value="any">All Types</option>
                                        {modularSchema.postTypes.map(type => (
                                            <option key={type.name} value={type.name}>
                                                {type.label}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="schema-filter-group">
                                    <label className="schema-label">Search</label>
                                    <input
                                        type="text"
                                        className="schema-input"
                                        value={postSearch}
                                        onChange={(e) => {
                                            setPostSearch(e.target.value);
                                            setSelectedPost(null);
                                        }}
                                        placeholder="Search posts..."
                                    />
                                </div>
                            </div>

                            <div className="schema-posts-layout">
                                <div className="schema-posts-list">
                                    <h3>Select a Post</h3>
                                    <div className="schema-posts-scroll">
                                        {postsList.length === 0 ? (
                                            <p className="schema-empty">No posts found</p>
                                        ) : (
                                            postsList.map(post => (
                                                <div
                                                    key={post.id}
                                                    className={`schema-post-item ${selectedPost === post.id ? 'active' : ''}`}
                                                    onClick={() => loadPostSchema(post.id)}
                                                >
                                                    <div className="schema-post-title">{post.title}</div>
                                                    <div className="schema-post-meta">
                                                        <span className="schema-post-type">{post.type}</span>
                                                        {post.edit_link && (
                                                            <a
                                                                href={post.edit_link}
                                                                target="_blank"
                                                                className="schema-post-edit"
                                                                onClick={(e) => e.stopPropagation()}
                                                            >
                                                                Edit
                                                            </a>
                                                        )}
                                                    </div>
                                                </div>
                                            ))
                                        )}
                                    </div>
                                </div>

                                {selectedPost && (
                                    <div className="schema-post-editor">
                                        <h3>Edit Schema</h3>
                                        <ToggleSwitch
                                            enabled={postSchema.enabled}
                                            onChange={(val) => setPostSchema({ ...postSchema, enabled: val })}
                                            label="Enable Schema"
                                        />
                                        <div className="schema-editor-section">
                                            <label className="schema-label">Schema JSON-LD Code</label>
                                            <SchemaEditor
                                                value={postSchema.schema}
                                                onChange={(val) => setPostSchema({ ...postSchema, schema: val })}
                                            />
                                        </div>
                                        <div className="schema-actions">
                                            <button
                                                className="schema-btn schema-btn-primary"
                                                onClick={savePostSchema}
                                                disabled={saving}
                                            >
                                                {saving ? 'Saving...' : 'Save Schema'}
                                            </button>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

// Render the component
const root = ReactDOM.createRoot(document.getElementById('modular-schema-admin'));
root.render(<SchemaAdmin />);

