const { registerBlockType } = wp.blocks;
const { InnerBlocks, RichText } = wp.blockEditor;

registerBlockType('meu-plugin/meu-acordeao', {
    title: 'Acordeão',
    icon: 'menu',
    category: 'design',
    attributes: {
        titulo: {
            type: 'string',
            source: 'text',
            selector: '.acordeao-item',
        },
    },
    edit: ({ attributes, setAttributes }) => {
        const { titulo } = attributes;

        const onChangeTitulo = (newTitulo) => {
            setAttributes({ titulo: newTitulo });
        };

        return (
            <div>
                <RichText
                    tagName="button"
                    className="acordeao-item"
                    value={titulo}
                    onChange={onChangeTitulo}
                    placeholder="Item do Acordeão"
                />
                <div className="acordeao-content">
                    <InnerBlocks />
                </div>
            </div>
        );
    },
    save: ({ attributes }) => {
        return (
            <div>
                <button className="acordeao-item">{attributes.titulo}</button>
                <div className="acordeao-content">
                    <InnerBlocks.Content />
                </div>
            </div>
        );
    },
});
