<?php

namespace Common\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * email_template
 *
 * @ORM\Table(name="email_template")
 * @ORM\Entity(repositoryClass="Common\Model\Repository\email_templateRepository")
 */
class email_template
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="template_name", type="string", length=255)
     */
    private $templateName;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="text")
     */
    private $template;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set templateName.
     *
     * @param string $templateName
     *
     * @return email_template
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;

        return $this;
    }

    /**
     * Get templateName.
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Set template.
     *
     * @param string $template
     *
     * @return email_template
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
